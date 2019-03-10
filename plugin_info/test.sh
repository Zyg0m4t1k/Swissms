#!/bin/bash

# This script sends sms using the swisscom API SMS token Validation

#expects to have the message to send in argument
text="tutu"

curl -X POST \
  https://api.swisscom.com/v1/tokenvalidation \
  -H 'accept: application/json' \
  -H 'cache-control: no-cache' \
  -H 'client_id: tPJrdZDRCV66YSdFx15nLJdjmAPK8xwL' \
  -H 'content-type: application/json' \
  -H 'postman-token: 6550ab71-268e-74e4-aa32-9590f890c2f0' \
  -H 'scs-version: 1' \
  -d '{
   "to":"+41787223590",
   "text":"'"$text"'\n %TOKEN%",
   "tokenType":"SHORT_ALPHANUMERIC",
   "expireTime":60,
   "tokenLength":1
}'