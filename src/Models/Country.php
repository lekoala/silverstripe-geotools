<?php

namespace LeKoala\GeoTools\Models;

/**
 * A global country model
 */
class Country
{
    /**
     * Uppercased country code
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    public function __construct(string $code = null, string $name = null)
    {
        if ($code) {
            $code = strtoupper($code);
        }
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Create from a given source (array, pairs)
     *
     * Country::create('be,Belgium')
     * Country::create('be','Belgium')
     * Country::create(['be','Belgium'])
     *
     * @param mixed $source
     * @param array<string> $more
     * @return self
     */
    public static function create($source, ...$more)
    {
        if (!is_array($source)) {
            $source = explode(',', $source);
        }
        if (!empty($more)) {
            $source = array_merge($source, $more);
        }
        return new self($source[0], $source[1]);
    }

    /**
     * Get the uppercased country code
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set the country code
     */
    public function setCode(string $code): self
    {
        $this->code = strtoupper($code);
        return $this;
    }

    /**
     * Get the name of the country
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the country
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
