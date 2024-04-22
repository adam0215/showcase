# pattern =
#   Parsers.PatternParser.parse("This <num:int> is a number and this <str:string> is a \string")

# input = Parsers.InputParser.parse("This: 15 is a number and this hello is a string")
# pattern_map = Interpreter.read_pattern(pattern)

# IO.puts("-------------------Pattern:")
# IO.inspect(pattern)
# IO.puts("-------------------Input:")
# IO.inspect(input)
# IO.puts("-------------------Pattern Map:")
# IO.inspect(pattern_map)
# IO.puts("-------------------Comparison:")

input_valid =
  Interpreter.compare?(
    "This <num:int> is a number and this <str:string> is a \string",
    "This: 15 is a number and this 'hello' is string"
  )

# IO.inspect(input_valid, label: "\n\nInput validity and value map")

number_val = elem(input_valid, 1)["num"]
IO.puts("\n\nInput is valid: #{elem(input_valid, 0)}")
IO.puts("Input of 'num' is: #{elem(input_valid, 1)["num"]}\n\n")
