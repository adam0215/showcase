package main

import (
	"fmt"
	"net/http"
	"strconv"
	"time"

	"ollama-htmx/llm"
	"ollama-htmx/templates"
	"ollama-htmx/templates/components"
	"ollama-htmx/util"

	"github.com/a-h/templ"
)

func main() {
	chat := llm.NewChat()

	http.Handle("/", templ.Handler(templates.Index()))

	public := http.FileServer(http.Dir("./public"))
	http.Handle("/public/", http.StripPrefix("/public/", public))

	http.HandleFunc("/events", sseHandler)

	http.Handle("/vapi/message-list", templ.Handler(components.ChatMessageList(chat.Messages)))

	http.Handle("/vapi/completion", http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		query, _ := util.GetFormValue("query", r)

		chat.AppendMessage(query, "user")

		completion, err := completion(query, chat)

		if err != nil {
			return
		}

		chat.AppendMessage(completion, "assistant")

		fmt.Print(w, components.ChatMessageList(chat.Messages).Render(r.Context(), w))
	}))

	fmt.Println("Listening on :3000")
	http.ListenAndServe(":3000", nil)
}

func sseHandler(w http.ResponseWriter, r *http.Request) {
	// Set CORS headers to allow all origins. You may want to restrict this to specific origins in a production environment.
	w.Header().Set("Access-Control-Allow-Origin", "*")
	w.Header().Set("Access-Control-Expose-Headers", "Content-Type")

	w.Header().Set("Content-Type", "text/event-stream")
	w.Header().Set("Cache-Control", "no-cache")
	w.Header().Set("Connection", "keep-alive")

	for i := 0; i < 10; i++ {
		fmt.Fprint(w, util.FormatSSE("rand", strconv.Itoa(i)))
		time.Sleep(1 * time.Second)
		w.(http.Flusher).Flush()
	}

	fmt.Fprint(w, util.FormatSSE("EOS", ""))
}

func completion(query string, chat *llm.LLMChat) (string, error) {
	completion, err := chat.Llm.Complete(query)

	if err != nil {
		return "", err
	}

	return completion, nil
}
