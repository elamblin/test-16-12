<?php

namespace NASA;

class RoverController
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var int
     */
    private $limitX;

    /**
     * @var int
     */
    private $limitY;

    /**
     * @var string
     */
    private $heading;

    const DIRRECTION = ['N', 'E', 'S', 'W'];

    public function action(string $input)
    {
        $instruction = explode("\n", $input);
        list($x, $y) = explode(' ', $instruction[0]);
        $this->initMap((int) $x, (int) $y);
        $i = 1;
        while ($i < count($instruction)) {
            list($x, $y, $h) = explode(' ', $instruction[$i]);
            $this->initPosition((int) $x, (int) $y, $h);

            ++$i;
            $moveInstructions = str_split($instruction[$i]);
            foreach ($moveInstructions as $moveInstruction) {
                if ('L' === $moveInstruction || 'R' === $moveInstruction) {
                    $this->spin($moveInstruction);
                } elseif ('M' === $moveInstruction) {
                    $this->move();
                }
            }
            echo $this->showPosition();
            ++$i;
        }
    }

    private function initMap(int $x, int $y)
    {
        $this->limitX = $x;
        $this->limitY = $y;
    }

    private function initPosition(int $x, int $y, string $h)
    {
        $this->x = $x;
        $this->y = $y;
        $this->heading = $h;
    }

    private function move()
    {
        switch ($this->heading) {
            case 'S':
                if ($this->y - 1 >= 0) {
                    --$this->y;
                }
                break;
            case 'N':
                if ($this->y + 1 <= $this->limitY) {
                    ++$this->y;
                }
                break;
            case 'W':
                if ($this->x - 1 >= 0) {
                    --$this->x;
                }
                break;
            case 'E':
                if ($this->y + 1 <= $this->limitX) {
                    ++$this->x;
                }
                break;
        }
    }

    private function spin(string $direction)
    {
        $headingKey = array_search($this->heading, self::DIRRECTION);
        if ('R' === $direction) {
            ++$headingKey;
        } elseif ('L' === $direction) {
            --$headingKey;
        }

        if ($headingKey < 0) {
            $headingKey = count(self::DIRRECTION) - 1;
        } elseif ($headingKey === count(self::DIRRECTION)) {
            $headingKey = 0;
        }
        $this->heading = self::DIRRECTION[$headingKey];
    }

    private function showPosition(): string
    {
        return  "$this->x $this->y $this->heading \n";
    }
}

$test = '5 5
1 2 N
LMLMLMLMM
3 3 E
MMRMMRMRRM';
$roverController = new RoverController();
$roverController->action($test);
