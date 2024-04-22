package llm

import (
	"context"

	"github.com/tmc/langchaingo/llms"
	"github.com/tmc/langchaingo/llms/ollama"
)

type LLMChat struct {
	Llm      *LLM
	Messages []ChatMessage
}

type ChatMessage struct {
	Content string
	Author  string
}

type LLM struct {
	model string
}

func NewChat() *LLMChat {
	return &LLMChat{
		Llm:      &LLM{model: "llama3"},
		Messages: []ChatMessage{},
	}
}

func (c *LLMChat) AppendMessage(message string, author string) {
	m := ChatMessage{Content: message, Author: author}
	c.Messages = append(c.Messages, m)
}

func (l *LLM) Complete(query string) (string, error) {
	llm, err := ollama.New(ollama.WithModel(l.model))

	if err != nil {
		return "", err
	}

	ctx := context.Background()
	completion, err := llms.GenerateFromSinglePrompt(ctx, llm, query)
	if err != nil {
		return "", err
	}

	return completion, nil
}
