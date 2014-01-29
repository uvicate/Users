#!python
import json
import os
import MySQLdb
import string
import random
from datetime import datetime, date, time, timedelta

def insert_user_data(customer_json, installer_json, config_json):
	db_name = installer_json['prefix_name'] + '_oauth_main'
	db_conf = installer_json['database']
	db = MySQLdb.connect(host="localhost", user=db_conf['user'], passwd=db_conf['pass'], db=db_name)
	cur = db.cursor()

	org = customer_json['organization']
	usr = customer_json['user']

	#Create the organization
	columns = "("
	values = "("
	for o in org:
		columns += o + ", "
		values += "'" + org[o] + "', "
	columns = columns[:-2] + ")"
	values = values[:-2] + ")"
	query = 'INSERT INTO organizations ' + columns + ' VALUES ' + values
	cur.execute(query)
	org_id = cur.lastrowid
	
	#Create user
	columns = "("
	values = "("
	for u in usr:
		columns += u + ", "
		values += "'" + usr[u] + "', "
	columns = columns[:-2] + ")"
	values = values[:-2] + ")"
	query = 'INSERT INTO users ' + columns + ' VAlUES ' + values
	cur.execute(query)
	user_id = cur.lastrowid
	
	#Add user to organization
	cur.execute('INSERT INTO user_organizations (users_id, organizations_id) VALUES (' + str(user_id) + ', ' + str(org_id) + ')')
	
	db.commit()
	return user_id

def create_organization_databases(customer_json, installer_json, config_json):
	db_conf = installer_json['database']
	db = MySQLdb.connect(host="localhost", user=db_conf['user'], passwd=db_conf['pass'])
	cur = db.cursor()

	org_name = customer_json['organization_alias']

	prefix = installer_json['prefix_name']
	platforms = config_json['platforms']

	sql_path = '../sql/'
	for platform in platforms:
		path = sql_path + platform + '_client.sql'
		try:
			with open(path):
				db_name = prefix + '_' + platform + '_' +org_name
				print '* Creating ' + platform + ' Database.'

				#Step 1
				cur.execute('CREATE DATABASE IF NOT EXISTS ' + db_name + ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci')

				db_platform = MySQLdb.connect(host="localhost", user=db_conf['user'], passwd=db_conf['pass'], db= db_name)
				cur_platform = db_platform.cursor()

				fo = open(path, "r")
				db_script = fo.read()
				fo.close()

				if(db_script != ''):
					#Step 2
					cur_platform.execute(db_script)
		except IOError:
			print '*** Warning: Problem creating ' + platform + ' Organization\'s database.'

def forgotten_password(user_id, customer_json, installer_json, config_json):
	db_name = installer_json['prefix_name'] + '_oauth_main'
	db_conf = installer_json['database']
	db = MySQLdb.connect(host="localhost", user=db_conf['user'], passwd=db_conf['pass'], db=db_name)
	cur = db.cursor()

	now = datetime.now()
	add = timedelta(hours=2)
	expiracy =  now + add
	expiracy = expiracy.strftime("%Y-%m-%d %H:%M:%S")
	now = now.strftime("%Y-%m-%d %H:%M:%S")

	key = id_generator(60)

	cur.execute("INSERT INTO forgotten_password (user_id, date, expiracy, keypass) VALUES (" + str(user_id) + ", '" + str(now) +"', '" + str(expiracy) + "', '" + str(key) + "')")

	db.commit()

def id_generator(size=6, chars=string.ascii_uppercase + string.digits):
	return ''.join(random.choice(chars) for x in range(size))
	

#Installer parameters
fo = open("platform_installer.json", "r")
js = fo.read()
fo.close()
installer_json = json.loads(js)

#Customer parameters
fo = open("new_customer.json", "r")
js = fo.read()
fo.close()
customer_json = json.loads(js)

#General configurations
fo = open("config.json", "r")
js = fo.read()
fo.close()
config_json = json.loads(js)

# To create the new customer, several things must be done:
#
# 1) Create organization
# 2) Insert user's new data
# 3) Create required user's databases
# 4) Simulate a "lost password"
# 5) Send email with new user's data (and lost password request)
# 

# Step 1 and 2
user_id = insert_user_data(customer_json, installer_json, config_json)

# Step 3
create_organization_databases(customer_json, installer_json, config_json)

#Step 4 and 5
forgotten_password(user_id, customer_json, installer_json, config_json)