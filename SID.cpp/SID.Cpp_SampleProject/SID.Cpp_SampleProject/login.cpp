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
	std::string clientid = sid->createClientID("Windows CPP")["response_data"].asString();
	cout << clientid << endl;
	Json::Value value = sid->login(clientid, id, pw);
	Json::FastWriter writer;
	cout << writer.write(value) << endl;
	cout << value["response_data"][0].asString() << endl;
	system("pause");
}