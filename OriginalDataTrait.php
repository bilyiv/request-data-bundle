<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
trait OriginalDataTrait
{
    /**
     * @var array|null
     */
    private $originalData;

    /**
     * @return array|null $data
     */
    public function getOriginalData(): ?array
    {
        return $this->originalData;
    }

    /**
     * @param array|null $data
     *
     * @return self
     */
    public function setOriginalData(?array $data): self
    {
        $this->originalData = $data;

        return $this;
    }
}
