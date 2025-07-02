<?php

class Quiz {
    private ?int $idquiz;
    private ?string $titre;
    private ?string $date; // Updated to use 'date'

    public function __construct(?int $idquiz, ?string $titre, ?string $date = null) {
        $this->idquiz = $idquiz; // Initialization
        $this->titre = $titre;
        $this->date = $date; // Set the date (optional)
    }

    public function getIdquiz(): ?int {
        return $this->idquiz;
    }

    public function setIdquiz(?int $idquiz): void {
        $this->idquiz = $idquiz;
    }

    public function getTitre(): ?string {
        return $this->titre;
    }

    public function setTitre(?string $titre): void {
        $this->titre = $titre;
    }

    // Getter and Setter for the date
    public function getDate(): ?string {
        return $this->date;
    }

    public function setDate(?string $date): void {
        $this->date = $date;
    }
}

?>
