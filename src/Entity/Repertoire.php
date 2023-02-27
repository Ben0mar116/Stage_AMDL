<?php

namespace App\Entity;

use App\Repository\RepertoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepertoireRepository::class)
 */
class Repertoire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $untitule; 

  
    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="Repertoire", orphanRemoval=true)
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Repertoire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Repertoire::class, inversedBy="sousRepertoire")
     * @ORM\JoinColumn( referencedColumnName="id", onDelete="CASCADE") 
     */
    private $repertoireParent;
     /**
     * @ORM\OneToMany(targetEntity=Repertoire::class, mappedBy="repertoireParent")
     */
    private $sousRepertoire;


    public function __toString()
    {
        return $this->untitule;
    }
    public function __construct()
    {
        $this->sousRepertoire = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUntitule(): ?string
    {
        return $this->untitule;
    }

    public function setUntitule(string $untitule): self
    {
        $this->untitule = $untitule;

        return $this;
    }


    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setRepertoire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getRepertoire() === $this) {
                $document->setRepertoire(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getRepertoireParent(): ?self
    {
        return $this->repertoireParent;
    }

    public function setRepertoireParent(?self $repertoireParent): self
    {
        $this->repertoireParent = $repertoireParent;

        return $this;
    }
    /**
     * @return Collection<int, self>
     */
    public function getsousRepertoire(): Collection
    {
        return $this->sousRepertoire;
    }

    public function addsousRepertoire(self $subFile): self
    {
        if (!$this->sousRepertoire->contains($subFile)) {
            $this->sousRepertoire[] = $subFile;
            $subFile->setRepertoireParent($this);
        }

        return $this;
    }

    public function removesouRepertoire(self $subFile): self
    {
        if ($this->sousRepertoire->removeElement($subFile)) {
            // set the owning side to null (unless already changed)
            if ($subFile->getRepertoireParent() === $this) {
                $subFile->setRepertoireParent(null);
            }
        }

        return $this;
    }
}
