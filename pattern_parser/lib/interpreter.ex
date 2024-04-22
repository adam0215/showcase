defmodule Interpreter do
  def compare?(pattern, input) do
    tokenized_pattern = Parsers.PatternParser.parse(pattern)
    tokenized_input = Parsers.InputParser.parse(input)

    {input_valid, value_map} = map_and_compare(tokenized_pattern, tokenized_input)

    {input_valid, value_map}
  end

  defp map_and_compare(tokenized_pattern, tokenized_input) do
    {input_valid, value_map} =
      Enum.zip(tokenized_pattern, tokenized_input)
      |> Enum.reduce({true, %{}}, fn {p_tk, i_tk}, {is_valid, value_map} ->
        if !is_valid do
          {false, %{}}
        else
          key_value = check(p_tk, i_tk)

          case key_value do
            {nil, _} ->
              {true, value_map}

            {key, value} ->
              {true, Map.put(value_map, key, value)}

            _ ->
              {false, %{}}
          end
        end
      end)

    {input_valid, value_map}
  end

  defp check(%{name: key, type: :TYPE_STRING}, input_tk) do
    case Integer.parse(input_tk) do
      :error -> {key, input_tk}
      _ -> nil
    end
  end

  defp check(%{name: key, type: :TYPE_INT}, input_tk) do
    case Integer.parse(input_tk) do
      :error -> nil
      _ -> {key, Integer.parse(input_tk) |> elem(0)}
    end
  end

  defp check(_, input_tk), do: {nil, input_tk}

  def read_pattern(pattern), do: read_pattern(pattern, 0, %{})

  def read_pattern([], _, map), do: map

  def read_pattern([tk | rest], index, map) do
    updated_map =
      case tk do
        %{name: name_val, type: type_val} -> Map.put(map, index, {name_val, type_val})
        _ -> map
      end

    read_pattern(rest, index + 1, updated_map)
  end
end
