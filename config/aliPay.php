<?php

return [
    //应用ID,您的APPID。
    'app_id' => "2016092700605369",

    //商户私钥
    'merchant_private_key' => "MIIEowIBAAKCAQEAu0Eiy+9d1/SPm25LmaoYOl7sJMtmWsdReW1Ll3NGyBjC0nbJ+B5ycC9tZ35OLT5n+UHMgYN3mgCWVOqiWuOahtXQlA48hjtEtdhcWJKTUP4v2JHv0c278Xeikq/IAkW2QLQs43HVbAJY5DyhOhXO+YBIlkvz2F69m89If6kR2DcT7TGAbKr5x9XeYtAV9Fd23ttUR44qUh2chpiuWVW70QSK6BiWZWLQQSOFRlyW6+LtR3T/VEKkjpQHPGMHrOfSNbPL29MYeOIHQMn+n3SD1/DSsL6Yg3VkZyWOcJEyXU0Rw7gunecQtBa86tWo9coaVyCq2JTAqonIPIeIZ154uwIDAQABAoIBAGeLzUIYSzxv8Dly9Ds049DjTJHMJ/1q2pLa7UICbNGZX6IiLe0WaRHAKC9imLhMGhKrX/r+R9TRHlA2rzCzS2/kLEKy3KUdgRFNY0NXSj8vUCXiDUtnCNat4ShcK62V/wIon+nluK7RXXZYUI9eH+W4GmuB9IVhXlgad2yggU2ds+Mye3dIbODDMfIScOb48+nMjh7CvjPzN+rZQik1MDzN9jaUHcaW99vmskjsBFHi2FZI4G2Px5M10E8cxjCrysIgosCs4rRW1gs7UMkGQUiqIo9RH2xvElgfoWZdmaVF0+b7MOOzXztl2IXDGMtrdS/RfunKSuA8OOlxNavDOwECgYEA8uwBPPEytmf9Wv1nVbo4nzuR8isZIqZdiH+6NllV0nUU5ZDo3jQH55ohce8yJA3sJJ/apZDbjv+Gf2/dv4eW8oxGGRhGsus7GSjzs5JVD+o22S0t/HyYJqVC8ipWrcDGnp/jH5vT5MuNh31BxaoA++/g+aIi8ZMXvOmaJRvNDDMCgYEAxVXpaCVuSaOL+gjPsetizlm0XH43kPK4Mhrdst5EOXqxAlPV8oz7+rdLNw8RfWSjaaZZ4R7zbJD+ImGeybCMPmQQazXRiy6IAEaBqoav8CZiTTK5/DpNB+gvvR8+BY1FSHB4GNlbJbNg8cHsvDM6tDGgyf+QEQX9VqvOT/1K2VkCgYBksr5ko9uQz5SvxkGywMo9/7SlPYZi3bICJmhrpSa1YkFyVFqj2c+5CyR4FV6koXzVRcqePWk2Yug/JYE3px5Ely9rsauE+Yv7BwXW138ZZM6twlPAyWlNA863kiNyTWpIUSEWdkMlIdgeZScBqFJWHX8WHEb9+yqo7fEvFtIuDwKBgE7YMabb9cHUZ5QiOyHiY5sA+nBOOdNfNztFwri519aDb//3ES+VJwSYgaPhEGLid+w5fAXXgPkqwW9pQ7FgKdiv0GOVoECU+d/qS9qfb+Jq47Hmh6sAfFChc5mDxxsew8TasxFyntlkX2KY0gasDVN71LuAscYrd3pOsOMC2AoZAoGBAOBVvOTYSa9t43Y3pQEevOqM0pZyCAHa+Go5mQyQlRIQ1dqcxmK3oE0c1Y4Rgvm6EtRJYCHSr5CYnsioaCct+M8jEzRC/GLh6QU/dbXZeu3rKgTqaAQuJ67pqHU09oOebtDEbENOOMhxsj3rSy1Tlb3kdS133wtHSzOw1UD6bCvi",
    
    //异步通知地址
    'notify_url' => "http://www.tostudy.xyz/pay/notify_url",
    
    //同步跳转
    'return_url' => "http://www.tostudy.xyz/paySuccess",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5wTV7HA9zOmxaipMdB3oaFKG80PWkfHjh6hbbxa05vAdH85ejSOgMZ4Mo0JHfbH6/Q502hAFLiMF56z0CjslUvWPK0Y5wzaMYvJL5jd4lARAxNJTu03uhfXS7lS2im9lq4c0L4R/zi73q7hGuKhSQ4IBXhkiWnyqSGzKwFRKWV/C3UVbcfSmEWBJd1TbfYJ59+5n6PxE1Imh5HguVVZclQQoqmv5iiBYG+f4eua6nBeti9Dk4mmi96uvWg2ZEfYJ6DcIZjRzTo+LQIRRrzqXYYls0t/lWG1wloGR9xvYDShjo5pGPVq8Ki0KbPyIzyqjNQ6TSCD1sHWT1kOXrcCH2QIDAQAB",
];
