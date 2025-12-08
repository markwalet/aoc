<?php

namespace Tests\Year2020;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day4Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_4(): void
    {
        $passports = $this->getPassports();
        $validatedA = $passports->filter($this->validateRequiredFields(...));
        $validatedB = $validatedA->filter($this->extraValidation(...));

        $this->assertCount(216, $validatedA);
        $this->assertCount(150, $validatedB);
    }

    private function validateRequiredFields(array $passport): bool
    {
        foreach (['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'] as $required) {
            if (array_key_exists($required, $passport) === false) {
                return false;
            }
        }

        return true;
    }

    private function extraValidation(array $passport): bool
    {
        if ($passport['byr'] < 1920 || $passport['byr'] > 2002) {
            return false;
        }
        if ($passport['iyr'] < 2010 || $passport['iyr'] > 2020) {
            return false;
        }
        if ($passport['eyr'] < 2020 || $passport['eyr'] > 2030) {
            return false;
        }
        if (Str::endsWith($passport['hgt'], ['cm', 'in']) === false) {
            return false;
        }
        $height = substr($passport['hgt'], 0, -2);
        switch(substr($passport['hgt'], -2)) {
            case 'cm':
                if ($height < 150 || $height > 193) {
                    return false;
                }
                break;
            case 'in':
                if ($height < 59 || $height > 76) {
                    return false;
                }
                break;
        }

        if (preg_match('/^#[0-9a-f]{6}$/', $passport['hcl']) !== 1) {
            return false;
        }

        if (in_array($passport['ecl'], ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'], true) === false) {
            return false;
        }

        if (preg_match('/^[0-9]{9}$/', $passport['pid']) !== 1) {
            return false;
        }



        return true;
    }

    private function getPassports(): Collection
    {
        return collect(explode(PHP_EOL.PHP_EOL, $this->puzzleInput()))
            ->map(fn (string $passport) => $this->parsePassport($passport));
    }

    private function parsePassport(string $passport): array
    {
        return collect(explode(' ', trim(str_replace(PHP_EOL, ' ', $passport))))
            ->mapWithKeys(function (string $line) {

                [$field, $value] = explode(':', $line, 2);

                return [$field => $value];
            })->toArray();
    }

}
