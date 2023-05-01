import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:gopay/constants.dart';

class SplashScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              "GoPay",
              style: GoogleFonts.poppins(
                textStyle: const TextStyle(
                    color: primaryColor,
                    fontWeight: FontWeight.bold,
                    fontSize: 36),
              ),
            ),
            const SizedBox(height: 20),
            Container(
                width: 250,
                child: const LinearProgressIndicator(
                  color: primaryColor,
                )),
          ],
        ),
      ),
    );
  }
}
