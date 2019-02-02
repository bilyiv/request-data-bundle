<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface OriginalDataInterface
{
    /**
     * @return array|null $data
     */
    public function getOriginalData(): ?array;

    /**
     * @param array|null $data
     *
     * @return mixed
     */
    public function setOriginalData(?array $data);
}
