#!/bin/bash

kill -9 `ps -ef |grep "CenterServer"|grep -v "grep"|awk '{print $2}'`
