<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient\clients;

use yii\authclient\OAuth2;

/**
 * Facebook allows authentication via Facebook OAuth.
 *
 * In order to use Facebook OAuth you must register your application at <https://developers.facebook.com/apps>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'facebook' => [
 *                 'class' => 'yii\authclient\clients\Facebook',
 *                 'clientId' => 'facebook_client_id',
 *                 'clientSecret' => 'facebook_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 * @see https://developers.facebook.com/apps
 * @see http://developers.facebook.com/docs/reference/api
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
 
  /*
 WARNING!!!!  this is bad class is used only for retrievings access details for instargramm posts
 */
 
class Instagram extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.facebook.com/dialog/oauth';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://graph.facebook.com/oauth/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://graph.facebook.com';
    /**
     * @inheritdoc
     */
    public $scope = 'email';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.5
     */
    public $attributeNames = [
        'name',
        'email',
    ];
    
    public static function getAddonSettings(){
        return [
        'posts' => [
                'IN_ACCESS_TOKEN' => ['value' => '', 'description' => 'Access token'],
                'IN_USERNAME' => ['value' => '', 'description' => 'Username'],
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        parent::applyAccessTokenToRequest($request, $accessToken);

        $data = $request->getData();
        $data['appsecret_proof'] = hash_hmac('sha256', $accessToken->getToken(), $this->clientSecret);
        $request->setData($data);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'facebook';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Facebook';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 860,
            'popupHeight' => 480,
        ];
    }
    
    public function prepareAttributes($attributes){
        $prepared = [];
        if (is_array($attributes)){
            //$prepared['gender'] = @$attributes['gender'] == 'male'?'m':'f';
            $prepared['email'] = @$attributes['email'];
            $name = $attributes['name'];
            $ex = explode(" ", $name);
            foreach($ex as $_k => $v){
                if (!tep_not_null($v)) unset($ex[$_k]);
            }
            while(($_f = array_shift($ex)) != false){
                if (!isset($prepared['firstname'])){
                    $prepared['firstname'] = $_f;
                } else {
                    $prepared['lastname'] .= $_f . " ";
                }
            }
            if (substr($prepared['lastname'], -1) == ' ') $prepared['lastname'] = substr($prepared['lastname'], 0, -1);
            //$prepared['avatar'] = @$attributes['image']['url'];
        }
        
        return $prepared;
    }    
}