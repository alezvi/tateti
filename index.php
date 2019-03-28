<?php 

class TaTeTi 
{
    const MAX_POSITIONS = 9;

    private $player = 1;

    private $positions = [];

    private $winner = 0;

    private $winnedCallback;

    private function performChecking() 
    {
        if (count($this->positions) > 5) {
            $matches = [
                'diagonals' => [[0,4,8], [3,4,6]],
                'horizontals' => [[0,3,6], [1,4,7], [2,5,8]],
                'verticals' => [[0,1,2], [3,4,5], [6,7,8]],
            ];

            foreach ($matches as $directions) {
                foreach ($directions as $k => $positions) {
                    $sum = 0;

                    foreach ($positions as $pos => $val) {
                        $sum += $val;
                    } 

                    if ($sum === 3) {
                        $this->setWinner(1);
                    }

                    if ($sum === 6) {
                        $this->setWinner(0);
                    }
                }
            }
        }
    }

    private function setWinner($player) 
    {
        $this->winner = $player;

        call_user_func($this->winnedCallback, $this->winner);
    }

    public function mark($x)
    {
        if ($x > self::MAX_POSITIONS) {
            throw new \OutOfRangeException('Invalid position');
        }

        if (! isset($this->positions[$x])) {
            $this->positions[$x] = $this->player;
            $this->togglePlayer();
            $this->performChecking();
            return $this;
        }

        throw new \InvalidArgumentException("Not empty place");
    }

    private function togglePlayer() 
    {
        if ($this->player === 1) {
            $this->player = 2;
            return;
        }

        $this->player = 1;
    }

    public function hasWinner() 
    {
        return null !== $this->winner;
    }

    public function getWinner() 
    {
        return $this->winner;
    }

    public function onWinned(callable $action) 
    {
        $this->winnedCallback = $action;
    }
}

$tateti = new TaTeTi;

$tateti->onWinned(function ($winner) {
    echo 'El juego ha sido ganado por ' . $winner;
});

// X
$tateti->mark(0);

// O
$tateti->mark(2);

// X
$tateti->mark(3);

// O
$tateti->mark(5);

// X
$tateti->mark(6);

// O
$tateti->mark(8);
