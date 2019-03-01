<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("title", message="Title is already used")
 */
class Property
{

    const STATUS = [
        1 => "Sale",
        2 => "Rent"
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Title can't be empty")
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Your title must be at least {{ limit }} characters long",
     *      maxMessage = "Your title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Description can't be empty")
     * @Assert\Regex("/^\w+/")
     */
    private $description;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="cover_image", fileNameProperty="coverImage")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coverImage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Adresse can't be empty")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Area can't be empty")
     * @Assert\Range(
     *   min = 10,
     *   max = 400,
     *   minMessage = "You must be at least {{ limit }}",
     *   maxMessage = "You cannot be taller than {{ limit }}"
     * )
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Bedrooms can't be empty")
     * @Assert\Range(
     *   min = 0,
     *   max = 10,
     *   minMessage = "You must be at least {{ limit }}",
     *   maxMessage = "You cannot be taller than {{ limit }}"
     * )
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Bathrooms can't be empty")
     * @Assert\Range(
     *   min = 0,
     *   max = 10,
     *   minMessage = "You must be at least {{ limit }}",
     *   maxMessage = "You cannot be taller than {{ limit }}"
     * )
     */
    private $bathrooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Garage can't be empty")
     * @Assert\Range(
     *   min = 0,
     *   max = 10,
     *   minMessage = "You must be at least {{ limit }}",
     *   maxMessage = "You cannot be taller than {{ limit }}"
     * )
     */
    private $garage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="City can't be empty")
     * @Assert\Length(
     *   min = 2,
     *   max = 80,
     *   minMessage = "City must be at least {{ limit }} characters long",
     *   maxMessage = "City cannot be longer than {{ limit }} characters"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Price can't be empty")
     * @Assert\Regex("/^([1-9][0-9]*|0)(\.[0-9]{2})?$/", message="Price is not valid")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Types", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $types;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="property", orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug() : ? string
    {
        return $this->slug;
    }

    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initSlug()
    {
        $this->slug = (new Slugify())->slugify($this->title);
    }

    public function getDescription() : ? string
    {
        return $this->description;
    }

    public function setDescription(string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(? File $imageFile = null) : void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile() : ? File
    {
        return $this->imageFile;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function statusProperty()
    {
        return self::STATUS[$this->getStatus()];
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): self
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getGarage(): ?int
    {
        return $this->garage;
    }

    public function setGarage(int $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getCity() : ? string
    {
        return $this->city;
    }

    public function setCity(string $city) : self
    {
        $this->city = $city;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function fixPrice()
    {
        return number_format($this->getPrice(), 2, ',', ' ');
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function PrePersist()
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function getTypes(): ?Types
    {
        return $this->types;
    }

    public function setTypes(?Types $types): self
    {
        $this->types = $types;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProperty($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProperty() === $this) {
                $image->setProperty(null);
            }
        }

        return $this;
    }

}
