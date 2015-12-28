


#后台服务架构
如今高并发服务架构有如下几种：多进程，多线程，非阻塞/异步IO(callback)已经coroutine模型。

-----------
[TOC]

##多进程
这种模型在linux下广泛使用，比如apache，主进程负责监听和管理连接，而具体的业务处理都会交给子进程来处理。

这种架构的最大的好处是隔离性，子进程crash不会影响到父进程。缺点就是对系统的负载过重，这种模型适用于那种不需要太多并发量的服务器程序。另外，进程间的通讯效率也是瓶颈，采用share memory技术来减低通信开销。

##多线程
多线程对于进程来说，开销稍小，但是对于线程共用变量时，需要加锁，程序的正确性比较难以保证。

##callback-非阻塞/异步IO
这种架构的特点是适用非阻塞的IO，这样服务器不需要等待，可以使用一个线程即可达到高性能，比如nginx。在linux中基于select, epoll实现的。但是关键点是所有操作都必须是非阻塞的。缺点是编程复杂，连续的代码的流程切成多个片段，另外也不能充分发挥多核的能力。

##Corountine-协程
协程是用户层实现的一种多任务处理机制，它的本质还是串行的，只是通过保存stack来切换任务。
coroutine实现大致分为两种：
>第一种称为stackful，借用C的Stack作为每个Coroutine的Stack空间，在linux下有getcontext(), setcontext(), windows下有fiber来使用或者使用setjump和longjmp。但是由于栈空间是又是编译器来分配的，如果有大量的coroutine存在的情况下，会造成大量的栈空间浪费，并且setjump和longjmp会造成很大的cache miss。
>第二种称为stacklass，采用虚拟机方式，对于大部分的虚拟机来说往往采用全局栈的方式来进行函数的调用，例如lua，这种情况下创建多个coroutine的栈空间的开销就会非常小，并且保存和记录返回link的开销也会比较小，因为你不需要保存register set等等需要保存task切换的东西。


