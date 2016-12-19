
# face 
 >本版本可以用于使用的旷视平台的人工智能开发平台[https://www.faceplusplus.com.cn](https://www.faceplusplus.com.cn)

# 安装

1.安装包文件

```shell
  composer require costa92/face
```

#配置
laravel 应用
1.注册 ServiceProvider: 

```php
   Costa92\Face\FaceServiceProvider::class,
``` 
2. 创建配置文件：

```shell
   php artisan vendor:publish
```
3. 请修改应用根目录下的 `config/face.php` 中对应的项即可；

4. （可选）添加外观到 `config/app.php` 中的 `aliases` 部分:
  ```php
  'Face' => Costa92\Face\FaceServiceProvider::class,
  ```
5. 在 ENV 中配置以下选项：
 
```
FACE_API_KEY =xxxxxx
FACE_API_SECRET =xxxxx
```
6.使用

1.detect 方法 平台接口地址 [https://console.faceplusplus.com.cn/documents/4888373](https://console.faceplusplus.com.cn/documents/4888373)
```
/**
  * 分析图片是否为人脸图片
  * @param $img_url
  * @param string $parems   smiling,gender,age
  * $param float $type  true:image_url(网络地址)  false:image_file(本地路径)默认是true
  * @return array|bool|mixed
  */

  Face::detect($img_url,'miling,gender,age');
```

2.compare 方法 平台接口地址 [https://console.faceplusplus.com.cn/documents/4887586](https://console.faceplusplus.com.cn/documents/4887586)


```
/**
     *
     * 将两个人脸进行比对，来判断是否为同一个人。支持传两张图片进行比对，或者一张图片与一个已知的face_token比对，也支持两个face_token进行比对。使用图片进行比对时会选取图片中检测到人脸尺寸最大的一个人脸。
     * @param $first_img   值
     * @param $first_type  1:face_token1 ,2:image_url1 3:image_file1
     * @param $second_img  值
     * @param $second_type 1:face_token2 ,2:image_url2 3:image_file2
     * @return array|mixed
     */
     Face::compare($first_img,$first_type,$second_img,$second_type);
```
3.search 方法 平台接口地址 [https://console.faceplusplus.com.cn/documents/4888381](https://console.faceplusplus.com.cn/documents/4888381)
```
/**
     *
     * 在Faceset中找出与目标人脸最相似的一张或多张人脸。支持传入face_token或者直接传入图片进行人脸搜索。使用图片进行比对时会选取图片中检测到人脸尺寸最大的一个人脸。
     * @param $search_img
     * @param $img_type 1:face_token ,2:image_url 3:image_file
     * @param $ident  true:faceset_token,false:outer_id
     * $param $return_result_count int 返回比对置信度最高的n个结果，范围[1,5]。默认值为1
     * @param $ident_type
     */
     
     Face::search($search_img,$img_type,$ident,$ident_type=true,$return_result_count=1);
```
.....

后面还有接口的，都封装好了，这里就不每一个方法都写了！可以去代码中看，都有详细的文字说明

## License
MIT

