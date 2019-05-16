#!/usr/bin/env python

import logging
logging.basicConfig(level=logging.DEBUG, format='%(relativeCreated)6d %(threadName)s %(message)s', filename='camera_log.log')
logging.warning("TEST")
logging.warning('test')
from picamera import PiCamera
from time import sleep
import os
import re

logging.warning('This will get logged to a file')

filename = "piikuva_"
regex = filename + "(\d+)\.jpg"
matchlist = []

try:
	timelapsePhotos = os.listdir("/home/pi/timelapsePhotos")

	logging.info("checking timelapsePhotos with regex " + regex + " ...")
	for file in timelapsePhotos:
		logging.debug(file)
		matchObj = re.match(regex, file)
		if matchObj is not None:
			print("appended : " + file)
			matchlist.append(matchObj)

	fileNo = 0

except Error as inst:
	logging.warning("inst: " + type(inst))
	logging.warning("inst.args : " + inst.args)

for matched in matchlist:
	#print("MatchObj group : " + matched.group())
	#print("MatchObj group 1 : " + matched.group(1))
	if int(matched.group(1)) >= fileNo:
		fileNo = int(matched.group(1))

fileNo = fileNo + 1

logging.warning("fileNo : " + str(fileNo))
logging.info("File no : " + str(fileNo))
try:
	camera = PiCamera()
	camera.start_preview()
	sleep(3)
	camera.capture('/home/pi/timelapsePhotos/' + filename + str(fileNo) + '.jpg')
	camera.stop_preview()
except Error as inst:
	logging.warning(type(inst))
	logging.warning(inst.args)
