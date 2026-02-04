<?php
declare(strict_types=1);

enum Operation: string
{
    case Add = '+';
    case Subtract = '-';
    case Multiply = '*';
    case Divide = '/';
}

class Calc
{
    public function __construct(
        public readonly int|float $num1,
        public readonly int|float $num2,
        public readonly Operation $cal
    ) {
    }

    public function calcMethod(): int|float|string
    {
        return match ($this->cal) {
            Operation::Add => $this->num1 + $this->num2,
            Operation::Subtract => $this->num1 - $this->num2,
            Operation::Multiply => $this->num1 * $this->num2,
            Operation::Divide => ($this->num2 == 0) ? "Error: Cannot divide by zero" : $this->num1 / $this->num2,
        };
    }
}