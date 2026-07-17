<?php


namespace dmstr\willnorrisImageproxy;

use Yii;

class Url
{

    /**
     * internal cache var that is used to get params from settings or env only once
     *
     * @var array
     */
    protected static $_paramCache = [];

    public static function image($imageSource, $preset = '')
    {
        // sanitize input
        $imageSource = ltrim((string)$imageSource, '/');
        if (empty($imageSource)) {
            return null;
        }
        $preset = trim((string)$preset, "/");
        $baseUrl = static::getBaseUrl();
        $prefix = static::getPrefix();
        $imageSourceFull = $imageSource . static::getSuffix();
     //   $signatureKey = static::getSignatureKey();

        // build remote URL
        $remoteUrl = implode('/', array_filter([$prefix, $imageSourceFull]));

        return static::getSigner()->createUrl(
            static::getBaseUrl(),
            $preset,
            $remoteUrl
        );

    }

    protected static function getSigner(): UrlGeneratorInterface
    {
       if(Yii::$container->has(UrlGeneratorInterface::class)) {
           return Yii::$container->get(UrlGeneratorInterface::class);
       }
       return new HmacUrlSigner();
    }



    /**
     * baseUrl for image src urls
     *
     * @return string|null
     */
    protected static function getBaseUrl()
    {
        if (!isset(static::$_paramCache['baseUrl'])) {
            static::$_paramCache['baseUrl'] = trim((string) Yii::$app->settings->get('imgBaseUrl', 'app.frontend'), "/");
        }
        return static::$_paramCache['baseUrl'];
    }

    /**
     * prefix used for imageSource
     *
     * @return string|null
     */
    protected static function getPrefix()
    {
        if (!isset(static::$_paramCache['prefix'])) {
            static::$_paramCache['prefix'] = trim((string) Yii::$app->settings->get('imgHostPrefix', 'app.frontend'), "/");
        }
        return static::$_paramCache['prefix'];
    }

    /**
     * suffix that will be appended to imageUrls
     *
     * @return string|null
     */
    protected static function getSuffix()
    {
        if (!isset(static::$_paramCache['suffix'])) {
            static::$_paramCache['suffix'] = Yii::$app->settings->get('imgHostSuffix', 'app.frontend');
        }
        return static::$_paramCache['suffix'];
    }

}
