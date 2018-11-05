<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

namespace Novactive\Bundle\eZFormBuilderBundle\Twitter;

interface TwitterClientInterface
{
    /**
     * Returns the embed version of a tweet from its $url.
     *
     * @param string $statusUrl
     *
     * @return string
     */
    public function getEmbed($statusUrl);
}
