<?php
namespace Costa92\Face\Services;
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2016/12/19
 * Time: 上午9:39
 */
use Costa92\Face\Http;
class FaceService
{
    public $_Http;
    public function __construct()
    {
        $this->_Http = new Http();
    }

    /**
     * 分析图片是否为人脸图片
     * @param $img_url
     * @param string $parems   smiling,gender,age
     * $param float $type  true:image_url(网络地址)  false:image_file(本地路径) 默认是true
     * @return array|bool|mixed
     */

    public function detect($img_url,$parems='',$type = true){
        if($img_url){
            $method = "detect";
            $image = $type?"image_url":"image_file";
            $params[$image]=$img_url;
            $params['return_attributes']=$parems?$parems:'smiling,gender,age';
            $rs = $this->_Http->execute($method,$params);
            $rs = $this->json_array($rs);
            return $rs;
        }
        return false;
    }

    /**
     *
     * 将两个人脸进行比对，来判断是否为同一个人。支持传两张图片进行比对，或者一张图片与一个已知的face_token比对，也支持两个face_token进行比对。使用图片进行比对时会选取图片中检测到人脸尺寸最大的一个人脸。
     * @param $first_img   值
     * @param $first_type  1:face_token1 ,2:image_url1 3:image_file1
     * @param $second_img  值
     * @param $second_type 1:face_token2 ,2:image_url2 3:image_file2
     * @return array|mixed
     */

    public function compare($first_img,$first_type,$second_img,$second_type){
        $method = "compare";
        $first_img_type = $this->select_img_type($first_type).'1';
        $params[$first_img_type]=$first_img;
        $second_img_type = $this->select_img_type($second_type).'2';
        $params[$second_img_type]=$second_img;
        $rs = $this->_Http->execute($method,$params);
        $rs = $this->json_array($rs);
        return $rs;
    }

    /**
     *
     * 在Faceset中找出与目标人脸最相似的一张或多张人脸。支持传入face_token或者直接传入图片进行人脸搜索。使用图片进行比对时会选取图片中检测到人脸尺寸最大的一个人脸。
     * @param $search_img
     * @param $img_type 1:face_token ,2:image_url 3:image_file
     * @param $ident  true:faceset_token,false:outer_id
     * $param $return_result_count int 返回比对置信度最高的n个结果，范围[1,5]。默认值为1
     * @param $ident_type
     */
    public function search($search_img,$img_type,$ident,$ident_type=true,$return_result_count=1){
        $method = "search";
        $idents = $ident_type?"faceset_token":"outer_id";
        $params[$idents]=$ident;
        $search_params=$this->select_img_type($img_type);
        $params[$search_params]=$search_img;
        $params['return_result_count']=$return_result_count;
        $rs = $this->_Http->execute($method,$params);
        $rs = $this->json_array($rs);
        return $rs;
    }

    private function select_img_type($type = 1){
        switch ($type){
            case 1:
                $search_params = "face_token";
                break;
            case 2:
                $search_params = "image_url";
                break;
            case 3:
                $search_params = "image_file";
                break;
            default:
                return array('success'=>false,'msg'=>"参数错误!");
        }
        return $search_params;
    }

    /**
     * 创建一个人脸的集合FaceSet，用于存储人脸标识face_token。一个FaceSet能够存储1,000个face_token。
     * @param string $display_name   人脸集合的名字，256个字符，不能包括字符^@,&=*'"
     * @param string $outer_id   全局唯一的FaceSet自定义标识 可以选择
     */

    public function Create($display_name = "first",$outer_id = ""){
        $method = "faceset/create";
        $params['display_name'] =$display_name;
        if($outer_id){
            $params['outer_id'] =$outer_id;
        }
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /**
     * 为一个已经创建的FaceSet添加人脸标识face_token。一个FaceSet最多存储1,000个face_token。
     * @param $ident FaceSet的标识
     * @param $face_tokens
     * @param bool $type  true:是FaceSet的标识(faceset_token) false:是用户提供的FaceSet标识 默认是true
     */
    public function addFace($ident,$face_tokens,$type=true){
        $method = "faceset/addface";
        $identify = $type?"faceset_token":"outer_id";
        $params[$identify]=$ident;
        $params['face_tokens'] = $face_tokens;
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /**
     * 移除一个FaceSet中的某些或者全部face_token
     * @param $ident FaceSet的标识
     * @param $face_tokens
     * @param bool $type  true:是FaceSet的标识(faceset_token) false:是用户提供的FaceSet标识 默认是true
     */

    public function removeFace($ident,$face_tokens,$type=true){
        $method = "faceset/removeface";
        $identify = $type?"faceset_token":"outer_id";
        $params[$identify]=$ident;
        $params['face_tokens'] = $face_tokens;
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /**
     * 获取一个FaceSet的所有信息
     * @param $ident FaceSet的标识
     * @param bool $type  true:是FaceSet的标识(faceset_token) false:是用户提供的FaceSet标识 默认是true
     * @return array|mixed
     */

    public function getDetail($ident,$type=true){
        $method = "faceset/getdetail";
        $identify = $type?"faceset_token":"outer_id";
        $params[$identify]=$ident;
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /**
     * @param $ident FaceSet的标识
     * @param bool $type  true:是FaceSet的标识(faceset_token) false:是用户提供的FaceSet标识 默认是true
     * @param int $check_empty
     * @return array|mixed  删除时是否检查FaceSet中是否存在face_token，默认值为1 0：不检查1：检查 如果设置为1，当FaceSet中存在face_token则不能删除
     *
     */

    public function delete($ident,$type=true,$check_empty = 1){
        $method = "faceset/delete";
        $identify = $type?"faceset_token":"outer_id";
        $params[$identify]=$ident;
        $params['check_empty']=$check_empty;
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /***
     *
     * 获取所有的FaceSet
     * @param $tags 包含需要查询的FaceSet标签的字符串，用逗号分隔
     * @param int $start 传入参数start，控制从第几个Faceset开始返回。返回的Faceset按照创建时间排序，每次返回1000个FaceSets。默认值为1。
     * @return array|mixed
     */
    public function getFaceSets($tags,$start =1){
        $method = "faceset/getfacesets";
        $params['tags']=$tags;
        $params['start']=$start;
        $rs = $this->_Http->execute($method,$params);
        $rs=$this->json_array($rs);
        return $rs;
    }

    /**
     *
     * 述通过传入在Detect API检测出的人脸标识face_token，分析得出人脸的五官关键点，人脸属性和人脸质量判断信息。最多支持分析5个人脸。
     * @param $face_tokens一个字符串，由一个或多个人脸标识组成，用逗号分隔。最多支持5个face_token。
     * @param int $return_landmark 是否检测并返回人脸五官和轮廓的83个关键点。1:检测 0:不检测 注：默认值为0
     * @param string $return_attributes
     * 否检测并返回根据人脸特征判断出的年龄，性别，微笑、人脸质量等属性，需要将需要检测的属性组织成一个用逗号分隔的字符串。
     * 目前支持：gender, age, smiling, glass, headpose,facequality,blur 顺序没有要求。
     * 默认值为 none ，表示不检测属性。请注意none如果与任何属性共用则都不检测属性。
     *
     * 参数 $return_landmark与$return_attributes 必选（至少检测一项）
     *
     * @return array|mixed
     */
    public function analyze($face_tokens,$return_landmark=0,$return_attributes=""){
        if($face_tokens){
            $method = "face/analyze";
            $params['face_tokens']=$face_tokens;
            $params['return_landmark']=$return_landmark;
            $params['return_attributes']=$return_attributes;
            $rs = $this->_Http->execute($method,$params);
            $rs=$this->json_array($rs);
            return $rs;
        }
        return array('success'=>false,'msg'=>'没有face_tokens参数');
    }

    /**
     * 通过传入在Detect API检测出的人脸标识face_token，获取一个人脸的关联信息，包括源图片ID、归属的FaceSet。
     * @param $face_token
     *
     */
    public function getFaceDetail($face_token){
        if($face_token){
            $method = "face/getdetail";
            $params['face_tokens']=$face_token;
            $rs = $this->_Http->execute($method,$params);
            $rs=$this->json_array($rs);
            return $rs;
        }
        return array('success'=>false,'msg'=>'没有face_tokens参数');
    }

    /**
     * 为检测出的某一个人脸添加标识信息，该信息会在Search接口结果中返回，用来确定用户身份。
     * @param $face_token 人脸标识face_token
     * @param $user_id  用户自定义的user_id，不超过255个字符，不能包括^@,&=*'"建议将同一个人的多个face_token设置同样的user_id。
     * @return array|mixed
     */

    public function setUserId($face_token,$user_id){
        if($face_token && $user_id){
            $method = "face/setuserid";
            $params['face_tokens']=$face_token;
            $params['user_id']=$user_id;
            $rs = $this->_Http->execute($method,$params);
            $rs=$this->json_array($rs);
            return $rs;
        }
        return array('success'=>false,'msg'=>'没有face_token或user_id参数');
    }

    /**
     * @param $result
     * @return mixed
     *  json 转成  array
     */
    private function json_array($result){
        if($result['http_code']==200){
            $result['body'] = json_decode( $result['body'] ,1);
            return  $result['body'];
        }
        return $result;
    }

}