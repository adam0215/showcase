defmodule Parsers.Token do
  def get("<"), do: :L_ANGLE
  def get(:L_ANGLE), do: :L_ANGLE

  def get(">"), do: :R_ANGLE
  def get(:R_ANGLE), do: :R_ANGLE

  def get(":"), do: :COLON
  def get(:COLON), do: :COLON

  def get("string"), do: :COLON
  def get(:TYPE_STRING), do: :TYPE_STRING
  def get("int"), do: :COLON
  def get(:TYPE_INT), do: :TYPE_INT

  def get(_), do: :IDENT
end

defmodule Parsers.Ident do
  def get_keyword("string"), do: :TYPE_STRING
  def get_keyword("int"), do: :TYPE_INT
  def get_keyword(ident), do: ident

  def is_datatype(:TYPE_STRING), do: true
  def is_datatype(:TYPE_INT), do: true
  def is_datatype(_), do: false

  def consume_ident([], ident) do
    {[], Utils.rev_join(ident)}
  end

  def consume_ident([" " | chars], ident) do
    {chars, Utils.rev_join(ident)}
  end

  def consume_ident(chars, ident) do
    [letter | rest] = chars

    peek_next = Utils.peek(chars)

    if Parsers.Token.get(peek_next) != :IDENT do
      {rest, Utils.rev_join([letter | ident])}
    else
      consume_ident(rest, [letter | ident])
    end
  end
end

defmodule Parsers.Expression do
  def consume_expression!(tokens), do: consume_expression!([], tokens, %{})

  def consume_expression!(consumed, rest, %{"name" => name_val, "type" => type_val}, true) do
    {Enum.reverse(consumed), rest, %{:name => name_val, :type => type_val}}
  end

  def consume_expression!(consumed, tokens, expression) do
    [tk | rest] = tokens
    head = [tk | consumed]

    peek_next = Utils.peek(tokens)
    # IO.puts("syntax: #{tk} #{peek_next} #{Parsers.Token.get(peek_next)}")

    syntax_error =
      Parsers.Token.get(peek_next)
      |> Parsers.PatternParser.Grammar.check_grammar_next(tk)

    if syntax_error != nil do
      raise Parsers.PatternParser.Errors.SyntaxError,
        message: Parsers.PatternParser.Errors.explain_error(syntax_error)
    else
      case tk do
        :L_ANGLE ->
          consume_expression!(head, rest, Map.put(expression, "name", peek_next))

        # Finished
        :R_ANGLE ->
          consume_expression!(head, rest, expression, true)

        :COLON ->
          consume_expression!(head, rest, Map.put(expression, "type", peek_next))

        _ ->
          consume_expression!(head, rest, expression)
      end
    end
  end
end

defmodule Parsers.PatternParser do
  def parse(input) do
    String.graphemes(input) |> consume_next([]) |> Enum.reverse() |> process_tokens!()
  end

  defp consume_next([], token_list), do: token_list
  # Ignore spaces
  defp consume_next([" " | chars], token_list), do: consume_next(chars, token_list)

  # Consume string
  defp consume_next(chars, token_list) do
    tokens = token_list

    [first | rest] = chars
    tk = Parsers.Token.get(first)

    {rest, tk} =
      case tk do
        :IDENT ->
          {rest_chars, ident} = Parsers.Ident.consume_ident(chars, [])
          # Return keyword ATOM if keyword, else word as string
          keyword = Parsers.Ident.get_keyword(ident)
          {rest_chars, keyword}

        _ ->
          {rest, tk}
      end

    tokens = [tk | tokens]
    consume_next(rest, tokens)
  end

  def process_tokens!(tokens), do: process_tokens!([], tokens)

  def process_tokens!(new_tokens, []), do: Enum.reverse(new_tokens)

  def process_tokens!(new_tokens, token_consumer) do
    [tk | rest] = token_consumer

    peek_next = Utils.peek(token_consumer)
    # IO.puts("syntax: #{tk} #{peek_next} #{Parsers.Token.get(peek_next)}")
    syntax_error =
      Parsers.Token.get(peek_next)
      |> Parsers.PatternParser.Grammar.check_grammar_next(tk)

    if syntax_error != nil do
      raise Parsers.PatternParser.Errors.SyntaxError,
        message: Parsers.PatternParser.Errors.explain_error(syntax_error)
    end

    if tk == :L_ANGLE do
      {_, rest, expr} = Parsers.Expression.consume_expression!(token_consumer)
      new_tokens = [expr | new_tokens]
      process_tokens!(new_tokens, rest)
    else
      process_tokens!([tk | new_tokens], rest)
    end
  end
end
