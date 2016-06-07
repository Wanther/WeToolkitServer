#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
import zipfile


if __name__ == '__main__':
	apk = zipfile.ZipFile(sys.argv[1], 'a', zipfile.ZIP_DEFLATED)
	emptyChannelFile = 'empty.txt'
	apk.write(emptyChannelFile, 'META-INF/channel_' + sys.argv[2] + ".txt")
	apk.close()