<?php
return [
  'view_replace_str'  =>  [
    '__PUBLIC__'=>'',
    '__PUBLIC_STATIC__'=>'/static',
    '__UPLOAD_THUMB__'=>'/uploads/thumb',
    '__UPLOAD_CACHE__'=>'/uploads/cache'
  ],
  'template'               => [
    // 模板路径
    'view_path'    =>ROOT_PATH. '/template/index/',
  ],
  'default_controller'     => 'Home',
  'session'                => [
    'prefix'         => 'module',
    'type'           => '',
    'auto_start'     => true,
  ],
  'log'                    => [
    // 日志记录方式，内置 file socket 支持扩展
    'type'  => '\\log\\Indexlog',
    // 日志保存目录
    'path'  => LOG_PATH,
    // 日志记录级别
    'level' =>  ['alert','notice'],
  ],
  //缓存
  'cache' =>  [
    // 使用复合缓存类型
    'type'  =>  'complex',
    // 默认使用的缓存
    'default'   =>  [
      // 驱动方式
      'type'   => 'File',
      // 缓存保存目录
      'path'   => CACHE_PATH,
    ],
    // 文件缓存
    'file'   =>  [
      // 驱动方式
      'type'   => 'file',
      // 设置不同的缓存保存目录
      'path'   => RUNTIME_PATH . 'file/',
    ],  
    // redis缓存
    'redis'   =>  [
      // 驱动方式
      'type'   => 'redis',
      // 服务器地址
      'host'       => '127.0.0.1',
    ],  
  ],
];
