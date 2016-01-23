#!/bin/python
#-!- encoding:utf8 -!-

import hashlib, requests

#
#
#
class RequestInterface:

	def __init__(self):
		self.response = ""

	def response(self):
		return self.response

	def responseCode(self):
		return self.response.status_code

	def responseText(self):
		return self.response.text

	def get(self, url, ssl_verify = True):
		self.response = requests.get(url, verify=ssl_verify)

	def post(self, url, params):
		self.response = requests.post(url, data=params) 

#
#
#
class DoleticBackendTester:

	def __init__(self):
		self.ri = RequestInterface()
		self.baseurl = "https://localhost"
		self.username = "test_username"
		self.password = "password"
		self.hash = hashlib.sha1(self.password)

	def initConnection(self):
		self.ri.get(self.baseurl+"/src/kernel/Main.php", False)
		self.printResponse()


	def printResponse(self):
		print("#----------------------------------#\nResponse code : %s\nReponse text :\n<------------------------------\n %s\n\n------------------------------>\n" % 
			(self.ri.responseCode(), self.ri.responseText()))
		
		
	def run(self):
		self.initConnection()

#-----------------------------------------------------------------------------------------#
# 										 SCRIPT 										  #
#-----------------------------------------------------------------------------------------#

tester = DoleticBackendTester()
tester.run()