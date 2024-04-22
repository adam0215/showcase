package util

import (
	"errors"
	"fmt"
	"net/http"
)

func GetFormValue(f string, r *http.Request) (string, error) {
	r.ParseForm()

	if !r.Form.Has(f) {
		return "", errors.New("provided field does not exist")
	}

	v := r.Form.Get("query")

	return v, nil
}

func FormatSSE(n string, b string) string {
	return fmt.Sprintf("event: %s\ndata: %s\n\n", n, b)
}
