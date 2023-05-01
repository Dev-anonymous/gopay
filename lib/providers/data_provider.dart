import 'package:flutter/foundation.dart';

import '../constants.dart';

import 'package:http/http.dart' as http;
import 'dart:convert';

class DataProvider {
  final http.Client httpClient = http.Client();

  Future<Map<String, dynamic>> login(
      {required username, required password}) async {
    try {
      const url = '${BASE_URL}auth/login';
      const headers = {
        'Content-Type': 'application/json',
      };
      final body = json.encode({
        'login': username,
        'password': password,
      });
      var response =
          await httpClient.post(Uri.parse(url), body: body, headers: headers);

      // if (response.statusCode == 200) {
      Map<String, dynamic> jsonResponse =
          Map<String, dynamic>.from(json.decode(response.body));

      // print('DATA FETCH CandidatsProvider');
      // print(candidatsModel);
      return jsonResponse;
      // } else {
      //   print('error: ${response.statusCode}');
      //   return jsonResponse;
      // }
    } catch (_) {
      print('ERREUR - LOGIN');

      print(_.toString());
    }
    return Future.error('error');
  }

  Future<Map<String, dynamic>> getSolde(
      {required String solde, required String token}) async {
    {
      try {
        var url = '${BASE_URL}solde/$solde';
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer 5|H310Au3XVwgrHNvH4Eu785A6o4QhrECDGPTVV1ub'
        };

        print('URL: $url');

        var response = await httpClient.get(Uri.parse(url), headers: headers);

        // if (response.statusCode == 200) {
        Map<String, dynamic> jsonResponse =
            Map<String, dynamic>.from(json.decode(response.body));

        // print('DATA FETCH CandidatsProvider');
        // print(candidatsModel);
        return jsonResponse;
        // } else {
        //   print('error: ${response.statusCode}');
        //   return jsonResponse;
        // }
      } catch (_) {
        print('ERREUR - GET SOLDE');

        print(_.toString());
      }
      return Future.error('error');
    }
  }

  Future<Map<String, dynamic>> getTransactions({required String token}) async {
    {
      try {
        var url = '${BASE_URL}transaction';
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer 5|H310Au3XVwgrHNvH4Eu785A6o4QhrECDGPTVV1ub'
        };

        print('URL: $url');

        var response = await httpClient.get(Uri.parse(url), headers: headers);
        Map<String, dynamic> jsonResponse =
            Map<String, dynamic>.from(json.decode(response.body));

        return jsonResponse;
      } catch (_) {
        print('ERREUR - GET TRANSACTIONS');

        print(_.toString());
      }
      return Future.error('error');
    }
  }

  Future<Map<String, dynamic>> getNumeroCompte({required String token}) async {
    {
      try {
        var url = '${BASE_URL}numero-compte';
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer 5|H310Au3XVwgrHNvH4Eu785A6o4QhrECDGPTVV1ub'
        };

        print('URL: $url');

        var response = await httpClient.get(Uri.parse(url), headers: headers);
        Map<String, dynamic> jsonResponse =
            Map<String, dynamic>.from(json.decode(response.body));

        return jsonResponse;
      } catch (_) {
        print('ERREUR - GET NUMERO DE COMPTE');

        print(_.toString());
      }
      return Future.error('error');
    }
  }

  Future<Map<String, dynamic>> getTransferts({required String token}) async {
    {
      try {
        var url = '${BASE_URL}demande-transfert';
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer 5|H310Au3XVwgrHNvH4Eu785A6o4QhrECDGPTVV1ub'
        };

        print('URL: $url');

        var response = await httpClient.get(Uri.parse(url), headers: headers);
        Map<String, dynamic> jsonResponse =
            Map<String, dynamic>.from(json.decode(response.body));

        return jsonResponse;
      } catch (_) {
        print('ERREUR - GET DEMANDES TRANFERTS');

        print(_.toString());
      }
      return Future.error('error');
    }
  }

  Future<Map<String, dynamic>> demanderTransfert(
      {required String numero,
      required String montant,
      required String devise,
      required String token}) async {
    {
      try {
        var url = '${BASE_URL}demande-transfert';
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer 5|H310Au3XVwgrHNvH4Eu785A6o4QhrECDGPTVV1ub'
        };

        final body = json.encode(
            {'telephone': numero, 'montant': montant, 'devise': devise});

        if (kDebugMode) {
          print('URL: $url');
        }

        var response =
            await httpClient.post(Uri.parse(url), body: body, headers: headers);
        Map<String, dynamic> jsonResponse =
            Map<String, dynamic>.from(json.decode(response.body));

        return jsonResponse;
      } catch (_) {
        if (kDebugMode) {
          print('ERREUR - POST DEMANDE TRANSFERT');
        }

        if (kDebugMode) {
          print(_.toString());
        }
      }
      return Future.error('error');
    }
  }
}
