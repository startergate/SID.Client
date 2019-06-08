#include <iostream>
#include "sid.h"

using namespace std;

int main() {
	SIDCpp* sid = new SIDCpp("SIDCppSampleProject");
	string id, pw;
	cout << "ID: ";
	cin >> id;
	cout << "PASSWORD: ";
	cin >> pw;
	std::string clientid = sid->createClientID("Windows CPP");
	cout << clientid << endl;
	Json::Value value = sid->login(clientid, id, pw);
	string sessid = value["sessid"].asString();
	cout << "Logged In." << endl;
	cout << "Your Session ID is: " << sessid << endl << endl;

	cout << "Continue to Get User Information." << endl;
	system("pause");

	cout << sid->getUserNickname(clientid, sessid) << endl; 

	cout << "Continue to Logout." << endl;
	system("pause");

	sid->logout(clientid, sessid);

	cout << "Logged Out. Session Destroyed." << endl;
	system("pause");
}