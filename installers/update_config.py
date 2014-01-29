#!python
import json
import os

def modify_main_config_file(installer_json, config_json):
	db_conf = installer_json['database']
	prefix = installer_json['prefix_name']
	platforms = config_json['platforms']

	root_path = '../'
	original = root_path + 'configure.original'
	path = root_path + 'configure.php'
	try:
		with open(original):
			fo = open(original, "r")
			main_file = fo.read()
			fo.close()

			#Replacing databases
			#-------------------
			db_replace = '$dbs = array('
			for platform in platforms:
				dbname = prefix + '_' + platform + '_main'

				db_replace += '\n\t"' + platform + '" => array('
				db_replace += '\n\t\t"db" => "mysql:host=localhost;dbname=' + dbname + '",'
				db_replace += '\n\t\t"user" => "' + db_conf['user'] + '", '
				db_replace += '\n\t\t"password" => "' + db_conf['pass'] + '"'
				db_replace += '\n\t\t), '

			db_replace = db_replace[:-2]
			db_replace += '\n\t);'

			main_file = main_file.replace('#--databases--#', db_replace)

			#Replacing cookie urls
			#---------------------
			cookie_replace = 'array('
			for cookie in installer_json['cookie_urls']:
				cookie_replace += '"' + cookie + '", '
			cookie_replace = cookie_replace[:-2]
			cookie_replace += ')'
			
			main_file = main_file.replace('#--cookie_domains--#', cookie_replace)

			#Replacing url
			#-------------
			main_file = main_file.replace('#--url--#', "'" + installer_json['url'] + "'")

			#Replacing rest url
			#------------------
			main_file = main_file.replace('#--rest_url--#', "'" + config_json['rest_url'] + "'")

			conf = open(path, "w+")
			conf.write(main_file)
			conf.close()

			print '* Edited configure.php file'
	except IOError:
		print '*** Warning: Problem modifying main config file.'

def modify_auth_config_file(installer_json, config_json):
	root_path = '../oauth/authorize/'
	original = root_path + 'login.original'
	path = root_path + 'login.json'

	try:
		with open(original):
			fo = open(original, "r")
			main_file = fo.read()
			fo.close()

			#Replacing rest url
			#------------------
			url = installer_json['url'] + config_json['rest_url']
			main_file = main_file.replace('#--url--#', url)

			conf = open(path, "w+")
			conf.write(main_file)
			conf.close()

			print '* Edited oauth/authorize/login.json file'
	except IOError:
		print '*** Warning: Problem modifying authorization file.'

#Installer parameters
fo = open("platform_installer.json", "r")
js = fo.read()
fo.close()
installer_json = json.loads(js)

#General configurations
fo = open("config.json", "r")
js = fo.read()
fo.close()
config_json = json.loads(js)

modify_main_config_file(installer_json, config_json)
modify_auth_config_file(installer_json, config_json)