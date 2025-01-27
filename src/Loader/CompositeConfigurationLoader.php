<?php

/**
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @license    https://choosealicense.com/licenses/gpl-3.0/ GNU General Public License v3.0
 * @copyright  Copyright (c) 2023 Brand Oriented sp. z o.o. (https://brandoriented.pl)
 * @copyright  Copyright (c) 2021 CI HUB GmbH (https://ci-hub.com)
 */

namespace CIHub\Bundle\SimpleRESTAdapterBundle\Loader;

use CIHub\Bundle\SimpleRESTAdapterBundle\Repository\DataHubConfigurationRepository;
use Pimcore\Bundle\DataHubBundle\Configuration;
use Webmozart\Assert\Assert;

final class CompositeConfigurationLoader
{
    /**
     * @param iterable<ConfigurationLoaderInterface> $loaders
     */
    public function __construct(private DataHubConfigurationRepository $dataHubConfigurationRepository, private iterable $loaders)
    {
    }

    /**
     * @return Configuration[]
     */
    public function loadConfigs(): array
    {
        return $this->dataHubConfigurationRepository->getList($this->getConfigTypes());
    }

    /**
     * @return array<int, string>
     */
    private function getConfigTypes(): array
    {
        $configTypes = [];

        foreach ($this->loaders as $loader) {
            /* @var ConfigurationLoaderInterface $loader */
            Assert::isInstanceOf($loader, ConfigurationLoaderInterface::class);

            $configTypes[] = $loader->configType();
        }

        return array_values(array_unique($configTypes));
    }
}
