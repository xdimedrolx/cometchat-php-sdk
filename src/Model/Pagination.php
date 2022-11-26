<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

use JMS\Serializer\Annotation as Serializer;

class Pagination
{
    /**
     * @var int|null
     * @Serializer\Type("int")
     */
    protected $total;

    /**
     * @var int|null
     * @Serializer\Type("int")
     */
    protected $count;

    /**
     * @var int|null
     * @Serializer\Type("int")
     * @Serializer\SerializedName("per_page")
     */
    protected $perPage;

    /**
     * @var int|null
     * @Serializer\Type("int")
     * @Serializer\SerializedName("current_page")
     */
    protected $currentPage;

    /**
     * @var int|null
     * @Serializer\Type("int")
     * @Serializer\SerializedName("total_pages")
     */
    protected $totalPages;

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    /**
     * @return int|null
     */
    public function getCurrentPage(): ?int
    {
        return $this->currentPage;
    }

    /**
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }
}
