defmodule Parsers.Word do
  def consume_word([], ident) do
    {[], Utils.rev_join(ident)}
  end

  def consume_word([" " | chars], ident) do
    {chars, Utils.rev_join(ident)}
  end

  def consume_word(chars, ident) do
    [letter | rest] = chars

    peek_next = Utils.peek(chars)

    if peek_next == " " do
      {rest, Utils.rev_join([letter | ident])}
    else
      consume_word(rest, [letter | ident])
    end
  end
end

defmodule Parsers.InputParser do
  def parse(input) do
    String.graphemes(input)
    |> consume_next([])
    |> Enum.reverse()
  end

  defp consume_next([], word_list), do: word_list
  # Ignore spaces
  defp consume_next([" " | chars], word_list), do: consume_next(chars, word_list)

  # Consume string
  defp consume_next(chars, word_list) do
    words = word_list

    [letter | rest] = chars

    {rest, word} =
      case letter do
        " " ->
          {rest, letter}

        _ ->
          {rest_chars, word} = Parsers.Word.consume_word(chars, [])
          {rest_chars, word}
      end

    words = [word | words]
    consume_next(rest, words)
  end
end
