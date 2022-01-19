# twogether

This utility from location "input/emp.txt" receive as an input a text file containing a list of employee dates of birth, in the following format with one entry per line:

[Person Name],[Date of Birth (yyyy-mm-dd)]
For example:
Steve,1992-10-14
Mary,1989-06-21

The utility output a CSV file detailing the dates we have cake for the current year, in the following format:
Date, Number of Small Cakes, Number of Large Cakes, Names of people getting cake

at "output/emp.txt"

## How to Test

1.  > composer install
2.  > composer dump-autoload
3.  > ./vendor/bin/phpunit --testdox

### Run the follwoing command from root folder

1.  > composer install
2.  > chmod +x cli
3.  > composer dump-autoload
4.  > For Linux / Mac update php interpreter location Update the path at first line of cli file  
    > to run in the terminal

    ./cli <emp.txt> (input file under "input" directory) (Output will be under "output" directory)

    or

    run > php ./cli <emp.txt>(input file under "input" directory) (Output will be under "output" directory)
