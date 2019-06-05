// sid.cpp
// require JsonCpp, libcurl

#pragma once

#include "json/json.h"
#include "json/writer.h"
#include "curl/curl.h"
#include <string>

namespace
{
	std::size_t callback(
		const char* in,
		std::size_t size,
		std::size_t num,
		std::string* out)
	{
		const std::size_t totalBytes(size * num);
		out->append(in, totalBytes);
		return totalBytes;
	}
}

class SID {
private:
	std::string clientName;

	Json::Value curlPost(std::string url, std::string data, std::string method = "POST") {
		CURL *curl = NULL;
		CURLcode res;

		int httpCode(0);
		std::unique_ptr<std::string> httpData(new std::string());

		curl_global_init(CURL_GLOBAL_ALL);

		curl = curl_easy_init();
		if (curl) {
			struct curl_slist *chunk = NULL;
			curl_easy_setopt(curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
			curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, callback);
			curl_easy_setopt(curl, CURLOPT_WRITEDATA, httpData.get());
			curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, method.c_str());
			curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data.c_str());
			curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, true);

			chunk = curl_slist_append(chunk, "Accept: application/json");
			chunk = curl_slist_append(chunk, "Content-Type: application/json");
			curl_easy_setopt(curl, CURLOPT_HTTPHEADER, chunk);
			curl_easy_setopt(curl, CURLOPT_VERBOSE, true);

			res = curl_easy_perform(curl);
			curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &httpCode);
			curl_easy_cleanup(curl);
			if (httpCode == 200)
			{
				Json::Value data;
				Json::Reader reader;
				reader.parse(httpData.get()->c_str(), data);

				return data;
			}
		}
		Json::Value error;
		error["error"] = 1;
		return error;
	}

public:
	SID(std::string clientName) {
		this->clientName = clientName;
	}

	~SID() {}

	int login(std::string clientid, std::string id, std::string pw) {
		Json::Value senddata;
		Json::FastWriter writer;
		senddata["type"] = "login";
		senddata["clientid"] = clientid;
		senddata["userid"] = id;
		senddata["password"] = pw;
		Json::Value userdata = this->curlPost("http://sid.donote.co:3000/api/session", writer.write(userdata));
	}
};