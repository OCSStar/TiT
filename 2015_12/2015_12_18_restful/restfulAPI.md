#REST， SOAP以及RPC

本期的主题是讨论RESTful的web架构，以及现有的比较流行的协议SOAP之间的区别，还有RPC的概念

-----------
[TOC]

##REST 
###什么是REST？
>1）    REST不是具体协议，而是一种设计框架，全称（Representational State
>Transfer），由Roy Thomas Fielding在2000提出。REST
>指的是一组架构约束条件和原则。满足这些约束条件和原则的应用程序或设计就是
>RESTful。提出的背景是为了在符合架构原理的前提下，理解和评估以网络为基础的应用软件的架构设计，得到一个功能强、性能好、适宜通信的架构。

>2）虽然REST本身受Web技术的影响很深，
>但是理论上REST架构风格并不是绑定在HTTP上，只不过目前HTTP是唯一与REST相关的实例。
>所以我们这里描述的REST也是通过HTTP实现的REST。

###理解REST
>REST包括一下概念：
>>**资源与URI**
>>**统一资源接口**
>>**资源的表述**
>>**资源的链接**
>>**状态的转移**

####资源与URI
>REST全称是表述性状态转移，其实指的就是资源。**任何事物，只要有被引用到的必要，它就是一个资源。资源可以是实体(例如手机号码)，也可以只是一个抽象概念(例如价值)**
>。比如：某用户的手机号码，某用户的个人信息，两个产品之间的依赖关系等等。要让一个资源可以被识别，需要有个唯一标识，在Web中这个唯一标识就是URI（Uniform
>Resource
>Identifier）。URI既可以看成是资源的地址，也可以看成是资源的名称。**URI的设计应该遵循可寻址性原则，具有自描述性，需要在形式上给人以直觉上的关联**。比如：https://github.com/git/git/blob/master/block-sha1/sha1.h。下面设计URI技巧：
>>使用_或-来让URI可读性更好
>>使用/来表示资源的层级关系
>>使用?来过滤资源
>>,或;可以用来表示同级资源的关系，比如git比较两次的diff的差异，/git/git/compare/master...next

####统一资源接口
>RESTFul架构应该遵循统一接口原则，统一接口包含了一组受限的预定义的操作，不论什么样的资源，都是通过使用相同的接口进行资源的访问。接口应该使用标准的HTTP方法如GET，PUT和POST，并遵循这些方法的语义。
>如果按照HTTP方法的语义来暴露资源，那么接口将会拥有安全性和幂等性的特性，例如GET和HEAD请求都是安全的，
>无论请求多少次，都不会改变服务器状态。而GET、HEAD、PUT和DELETE请求都是幂等的，无论对资源操作多少次，
>结果总是一样的，后面的请求并不会产生比第一次更多的影响。

>>GET
>>>安全且幂等
>>>获取表示
>>>变更时获取表示（缓存）
>>>200（OK） - 表示已在响应中发出
>>>204（无内容） - 资源有空表示
>>>301（Moved Permanently） - 资源的URI已被更新
>>>303（See Other） - 其他（如，负载均衡）
>>>304（not modified）- 资源未更改（缓存）
>>>400（bad request）- 指代坏请求（如，参数错误）
>>>404（not found）- 资源不存在
>>>406（not acceptable）- 服务端不支持所需表示
>>>500 （internal server error）- 通用错误响应
>>>503 （Service Unavailable）- 服务端当前无法处理请求

>>POST
>>>不安全且不幂等
>>>使用服务端管理的（自动产生）的实例号创建资源
>>>创建子资源
>>>部分更新资源
>>>如果没有被修改，则不过更新资源（乐观锁）
>>>200（OK）- 如果现有资源已被更改
>>>201（created）- 如果新资源被创建
>>>202（accepted）- 已接受处理请求但尚未完成（异步处理）
>>>301（Moved Permanently）- 资源的URI被更新
>>>303（See Other）- 其他（如，负载均衡）
>>>400（bad request）- 指代坏请求
>>>404 （not found）- 资源不存在
>>>406 （not acceptable）- 服务端不支持所需表示
>>>409 （conflict）- 通用冲突
>>>412 （Precondition Failed）- 前置条件失败（如执行条件更新时的冲突）
>>>415 （unsupported media type）- 接受到的表示不受支持
>>>500 （internal server error）- 通用错误响应
>>>503 （Service Unavailable）- 服务当前无法处理请求

>>PUT
>>>不安全但幂等
>>>用客户端管理的实例号创建一个资源
>>>通过替换的方式更新资源
>>>如果未被修改，则更新资源（乐观锁）
>>>200 （OK）- 如果已存在资源被更改
>>>201 （created）- 如果新资源被创建
>>>301（Moved Permanently）- 资源的URI已更改
>>>303 （See Other）- 其他（如，负载均衡）
>>>400 （bad request）- 指代坏请求
>>>404 （not found）- 资源不存在
>>>406 （not acceptable）- 服务端不支持所需表示
>>>409 （conflict）- 通用冲突
>>>412 （Precondition Failed）- 前置条件失败（如执行条件更新时的冲突）
>>>415 （unsupported media type）- 接受到的表示不受支持
>>>500 （internal server error）- 通用错误响应
>>>503 （Service Unavailable）- 服务当前无法处理请求

>>DELETE
>>>不安全但幂等
>>>删除资源
>>>200 （OK）- 资源已被删除
>>>301 （Moved Permanently）- 资源的URI已更改
>>>303 （See Other）- 其他，如负载均衡
>>>400 （bad request）- 指代坏请求
>>>404 （not found）- 资源不存在
>>>409 （conflict）- 通用冲突
>>>500 （internal server error）- 通用错误响应
>>>503 （Service Unavailable）- 服务端当前无法处理请求

>统一接口并不阻止你扩展方法，只要方法对资源的操作有着具体的、可识别的语义即可，并能够保持整个接口的统一性。
>像WebDAV就对HTTP方法进行了扩展，增加了LOCK、UPLOCK等方法。而github的API则支持使用PATCH方法来进行issue的更新

>统一资源接口要求使用标准的HTTP方法对资源进行操作，所以URI只应该来表示资源的名称，而不应该包括资源的操作。
>通俗来说，URI不应该使用动作来描述。

####资源的表述
>资源在外界的具体呈现，可以有多种表述(或成为表现、表示)形式,在客户端和服务端之间传送的也是资源的表述，而不是资源本身。
>例如文本资源可以采用html、xml、json等格式，图片可以使用PNG或JPG展现出来。
>资源的表述包括数据和描述数据的元数据，例如，HTTP头“Content-Type”
>就是这样一个元数据属性。客户端可以通过Accept头请求一种特定格式的表述，服务端则通过Content-Type告诉客户端资源的表述形式。例如：
```
#request
GET https://api.github.com/orgs/github HTTP/1.1
Accept: application/json

#response
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{
        "login": "github";
            ...
}
```
>若服务器不支持，它应该返回一个HTTP 406响应，表示拒绝处理该请求。

####资源的链接
>对于资源来说，就是通过一个链接跳转到另一个链接，将不同的状态链接起来。对于设计RESTful的框架来说，是hypertext_driven的。例如：
```
#Requst
GET https://api.github.com/orgs/github/repos HTTP/1.1
Accept: application/json
#Response
HTTP/1.1 Status: 200 OK
Link: <https://api/github.com/orgs/github/repos?page=2>;rel="next"
Content-Type: application/json; charset=utf-8
[
  {"id": 1244;
    "owner": {
            "login": "octocat",
                "id":1,
                    "avatar_url":
                    "https://github.com/images/error/octocat_happy.gif",
                        "gravatar_id": "somehexcode",
                            "url": "http://api.github.com/users/octcat"
                                },
                                    "name": "Hello-World",
                                        "url":
                                        "https://api.github.com/repos/octocat/Hello-World",
                                            ...
                                              }
                                              ]
                                              ```
                                              >上述代码中，Link就是提示用户下一步可以做什么操作，即状态的跳转

####状态的转移
>对于RESTful架构来说，状态分为两种：**应用状态和资源状态**，客户端负责维护应用状态，而服务端维护资源状态。
>客户端与服务端的交互必须是无状态的，并在每一次请求中包含处理该请求所需的一切信息。
>服务端不需要在请求间保留应用状态，只有在接受到实际请求的时候，服务端才会关注应用状态。
>这种无状态通信原则，使得服务端和中介能够理解独立的请求和响应。
>在多次请求中，同一客户端也不再需要依赖于同一服务器，方便实现高可扩展和高可用性的服务端。

####补充
>Pluralization: 资源量
>CORS: 跨域问题
##与SOAP的比较
>the main difference between SOAP and REST is the degree of coupling between
>client and server implementations. A SOAP client works like a custom desktop
>application, tightly coupled to the server. There's a rigid contract between
>client and server, and everything is expected to break if either side changes
>anything. You need constant updates following any change.
##参考网站：
[1]http://blog.jobbole.com/41233/
[2]http://www.ruanyifeng.com/blog/2011/09/restful.html
[3]http://roy.gbiv.com/untangled/2008/rest-apis-must-be-hypertext-driven
[4]http://stackoverflow.com/questions/19884295/soap-vs-rest-differences


