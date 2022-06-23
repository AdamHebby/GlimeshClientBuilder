<?php

namespace GlimeshClientBuilder;

/**
 * Builder Configuration
 *
 * @author Adam Hebden <adam@adamhebden.com>
 * @copyright 2021 Adam Hebden
 * @license GPL-3.0-or-later
 * @package GlimeshClientBuilder
 */
class BuilderConfig
{
    protected string $rootDirectory = __DIR__ . '/../../';
    protected string|null $apiJsonFilePath;
    protected string|null $outputDirectory;
    protected string|null $namespace;
    protected array $standardDocBlock = [];

    /**
     * Get the value of rootDirectory
     */
    public function getRootDirectory(): string
    {
        return $this->rootDirectory;
    }

    /**
     * Set the value of rootDirectory
     */
    public function setRootDirectory(string $rootDirectory): self
    {
        $this->rootDirectory = $rootDirectory;

        return $this;
    }

    /**
     * Get the value of apiJsonFilePath
     */
    public function getApiJsonFilePath(): ?string
    {
        return $this->apiJsonFilePath;
    }

    /**
     * Set the value of apiJsonFilePath
     */
    public function setApiJsonFilePath(string $apiJsonFilePath): self
    {
        $this->apiJsonFilePath = $apiJsonFilePath;

        return $this;
    }

    /**
     * Get the value of outputDirectory
     */
    public function getOutputDirectory(): ?string
    {
        return $this->outputDirectory;
    }

    /**
     * Set the value of outputDirectory
     */
    public function setOutputDirectory(string $outputDirectory): self
    {
        $this->outputDirectory = $outputDirectory;

        return $this;
    }

    /**
     * Get the value of namespace
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Set the value of namespace
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the value of standardDocBlock
     */
    public function getStandardDocBlock(): string
    {
        return implode("\n", $this->standardDocBlock);
    }

    /**
     * Set the value of standardDocBlock
     */
    public function setStandardDocBlock(array $standardDocBlock): self
    {
        $this->standardDocBlock = $standardDocBlock;

        return $this;
    }
}
