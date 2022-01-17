# twogether

This utility from location "input/emp.txt" receive as an input a text file containing a list of employee dates of birth, in the following format with one entry per line:

[Person Name],[Date of Birth (yyyy-mm-dd)]
For example:
Steve,1992-10-14
Mary,1989-06-21

The utility output a CSV file detailing the dates we have cake for the current year, in the following format:
Date, Number of Small Cakes, Number of Large Cakes, Names of people getting cake

at "output/emp.txt"

## How to install

### Run the follwoing command from root folder

1.  > chmod +x cli
2.  > composer dump-autoload
3.  > For Linux / Mac if you know php location Update the path of php at first line of cli file  
    > to run in the terminal

    ./cli <emp.txt> <output.csv> (input file under "input" directory) (Output will be under "output" directory)

    or

    run > php ./cli <emp.txt> <output.csv> (input file under "input" directory) (Output will be under "output" directory)
