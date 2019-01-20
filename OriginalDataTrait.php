<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
trait OriginalDataTrait
{
    /**
     * @var null|array
     */
    private $originalData;

    /**
     * @return null|array $data
     */
    public function getOriginalData(): ?array
    {
        return $this->originalData;
    }

    /**
     * @param null|array $data
     * @return self
     */
    public function setOriginalData(?array $data): self
    {
        $this->originalData = $data;

        return $this;
    }
}