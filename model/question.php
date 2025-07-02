<?php

class Questions {
    private ?int $id; 
    private ?int $idquiz; 
    private ?string $question;
    private ?string $rep1;
    private ?string $rep2;
    private ?string $rep3;
    private ?string $repcorrect;

    public function __construct(?int $id, ?int $idquiz, ?string $question, ?string $rep1, ?string $rep2, ?string $rep3, ?string $repcorrect) {
        $this->id = $id;
        $this->idquiz = $idquiz;
        $this->question = $question;
        $this->rep1 = $rep1;
        $this->rep2 = $rep2;
        $this->rep3 = $rep3;
        $this->repcorrect = $repcorrect;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getIdquiz(): ?int {
        return $this->idquiz;
    }

    public function setIdquiz(?int $idquiz): void {
        $this->idquiz = $idquiz;
    }

    public function getQuestion(): ?string {
        return $this->question;
    }

    public function setQuestion(?string $question): void {
        $this->question = $question;
    }

    public function getRep1(): ?string {
        return $this->rep1;
    }

    public function setRep1(?string $rep1): void {
        $this->rep1 = $rep1;
    }

    public function getRep2(): ?string {
        return $this->rep2;
    }

    public function setRep2(?string $rep2): void {
        $this->rep2 = $rep2;
    }

    public function getRep3(): ?string {
        return $this->rep3;
    }

    public function setRep3(?string $rep3): void {
        $this->rep3 = $rep3;
    }

    public function getRepcorrect(): ?string {
        return $this->repcorrect;
    }

    public function setRepcorrect(?string $repcorrect): void {
        $this->repcorrect = $repcorrect;
    }
}

?>
