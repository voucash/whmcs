# whmcs接入VouCash
whmcs VouCash支付插件 支持USDT，关于 [VouCash](https://github.com/voucash/voucash)

### 设置

1. 下载 SDK

下载modules/gateways/voucash.php，并上传到modules/gateways目录中  
下载modules/gateways/voucash/callback.php，并上传到modules/gateways/voucash/目录中  


2. 进入管理后台，System Settings > Payment Gateways > All Payment Gateways，点击VouCash激活启用


### 兑现

1. 支付成功后，新的代金券将保存到`/tmp/voucher.txt`中，打开该文件

2. 复制代金券到 [VouCash提现](https://voucash.com/cn/redeem)

## 有问题和合作可以小飞机联系我们
 - telegram：@voucash
