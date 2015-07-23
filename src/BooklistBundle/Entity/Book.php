<?php

namespace BooklistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BooklistBundle\Entity\Repository\BookRepository")
 */
class Book {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="writer", type="string", length=255)
     */
    private $writer;

    /**
     * @var string
     *
     * @ORM\Column(name="editor", type="string", length=255)
     */
    private $editor;

    /**
     * @var string
     *
     * @ORM\Column(name="quality", type="smallint")
     */
    private $quality;

    /**
     * @var boolean
     *
     * @ORM\Column(name="to_sell", type="boolean")
     */
    private $toSell;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Book
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set writer
     *
     * @param string $writer
     * @return Book
     */
    public function setWriter($writer) {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get writer
     *
     * @return string 
     */
    public function getWriter() {
        return $this->writer;
    }

    /**
     * Set editor
     *
     * @param string $editor
     * @return Book
     */
    public function setEditor($editor) {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return string 
     */
    public function getEditor() {
        return $this->editor;
    }

    /**
     * Set quality
     *
     * @param string $quality
     * @return Book
     */
    public function setQuality($quality) {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return string 
     */
    public function getQuality() {
        return $this->quality;
    }

    /**
     * Set toSell
     *
     * @param boolean $toSell
     * @return Book
     */
    public function setToSell($toSell) {
        $this->toSell = $toSell;

        return $this;
    }

    /**
     * Get toSell
     *
     * @return boolean 
     */
    public function getToSell() {
        return $this->toSell;
    }

}
