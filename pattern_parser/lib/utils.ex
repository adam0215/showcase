defmodule Utils do
  def peek(chars) when length(chars) == 1, do: nil

  def peek(chars) do
    [letter | rest] = chars
    if rest != [], do: hd(rest), else: letter
  end

  def rev_join(ident), do: Enum.reverse(ident) |> Enum.join()
end
