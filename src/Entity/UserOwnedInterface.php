<?php

namespace App\Entity;

interface UserOwnedInterface {
    public function getCreatedBy(): ?User;
    public function setCreatedBy(?User $createdBy): self;
}