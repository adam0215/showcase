defmodule Parsers.PatternParser.Grammar do
  # Datatype must follow colon
  def check_grammar_next(next_tk, :COLON) do
    if Parsers.Ident.is_datatype(next_tk), do: nil, else: :MISSING_DATATYPE_AFTER_COLON
  end

  # Expected token to follow current
  def check_grammar_next(next_tk, :L_ANGLE) do
    case next_tk do
      :IDENT -> nil
      nil -> nil
      _ -> :MISSING_IDENTIFIER
    end
  end

  def check_grammar_next(next_tk, :R_ANGLE) do
    case next_tk do
      :IDENT -> nil
      nil -> nil
      _ -> :MISSING_IDENTIFIER
    end
  end

  def check_grammar_next(next_tk, :TYPE_STRING) do
    if next_tk == :R_ANGLE, do: nil, else: :MISSING_CLOSING_BRACKET
  end

  def check_grammar_next(next_tk, :TYPE_INT) do
    if next_tk == :R_ANGLE, do: nil, else: :MISSING_CLOSING_BRACKET
  end

  def check_grammar_next(_, _), do: nil
end

defmodule Parsers.PatternParser.Errors do
  def explain_error(error) do
    case error do
      :MISSING_DATATYPE_AFTER_COLON -> "A datatype (int, string, etc.) must follow a colon."
      :MISSING_IDENTIFIER -> "You are missing an identifier after a '<' or '>'"
      :MISSING_CLOSING_BRACKET -> "You are missing a closing '>' bracket after a datatype"
      _ -> "Something went wrong while parsing the pattern"
    end
  end

  defmodule SyntaxError do
    defexception message: "Bad syntax"
  end
end
