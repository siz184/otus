<? \Bitrix\Main\Loader::includeModule('rest');
use \Bitrix\Rest\RestException;
use Bitrix\Main\Application;

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandlerCompatible('rest', 'OnRestServiceBuildDescription', ['NewsRest',
'OnRestServiceBuildDescriptionHandler']);
class NewsRest extends \IRestService{
    public static function OnRestServiceBuildDescriptionHandler() {

        return [
            'otus.news' => [
            'otus.news.add' => [__CLASS__, 'add'],
            'otus.news.list' => [__CLASS__, 'getList'],
            'otus.news.update' => [__CLASS__, 'update'],
            'otus.news.delete' => [__CLASS__, 'delete'],
            ]
        ];
    }


    public static function add ($query, $nav, \CRestServer $server){

        $request = Application::getInstance()->getContext()->getRequest();
        $isPost = $request->isPost();

        if(!$isPost){

            throw new RestException(
                'Invalid HTTP method. Use POST',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }

        $fieldsToAdd = $request->getPost('fields');

        $result = \Bitrix\Iblock\Elements\ElementNewsTable::add($fieldsToAdd);

        if ($result->isSuccess()) {

            $id = $result->getId();
            return $id;

        }else{
                throw new RestException(
                    json_encode($result->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                    RestException::ERROR_ARGUMENT,
                    \CRestServer::STATUS_OK
                );
        }
        
    }

    public static function update ($query, $nav, \CRestServer $server){

        $request = Application::getInstance()->getContext()->getRequest();
        $isPost = $request->isPost();

        if(!$isPost){

            throw new RestException(
                'Invalid HTTP method. Use POST',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }

        if(!self::exists($query['id'])){

            throw new RestException(
                'Element dooesn`t exist',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }

        $fieldsToUpdate = $request->getPost('fields');

        $result = \Bitrix\Iblock\Elements\ElementNewsTable::update($query['id'],$fieldsToUpdate);

        if ($result->isSuccess()) {

            return $query['id'];

        }else{
                throw new RestException(
                    json_encode($result->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                    RestException::ERROR_ARGUMENT,
                    \CRestServer::STATUS_OK
                );
        }
        
    }

    public static function delete ($query, $nav, \CRestServer $server){

        if(!self::exists($query['id'])){

            throw new RestException(
                'Element dooesn`t exist',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );

        }

            $result = \Bitrix\Iblock\Elements\ElementNewsTable::delete($query['id']);
            if($result->isSuccess()){
                return ['status' => 'ok'];
            }else{
                throw new RestException(
                    json_encode($result->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                    RestException::ERROR_ARGUMENT,
                    \CRestServer::STATUS_OK
                );
            }

    }

    public static function getlist ($query, $nav, \CRestServer $server){

        $navData = static::getNavData($nav, true);

        $res = \Bitrix\Iblock\Elements\ElementNewsTable::getList(
            [
                'filter' => $query['filter']?:[],
                'select' => $query['select']?:['*'],
                'order' => $query['order']?:['ID' => 'ASC'],
                'limit' => $navData['limit'],
                'offset' => $navData['offset'],
                'count_total' => true,
            ]
        );

        $result = [];
        while($item = $res->fetch())
        {
            $result[] = $item;
        }

        return static::setNavData(
            $result,
            array(
                "count" => $res->getCount(),
                "offset" => $navData['offset']
            )
        );
    
    }

    private static function exists($id){

        $element = \Bitrix\Iblock\Elements\ElementNewsTable::getByPrimary($id, [
            'select' => ['ID'],
        ])->fetch();

        if(empty($element['ID'])){
            return false;
        }else{
            return true;
        }

    }

}