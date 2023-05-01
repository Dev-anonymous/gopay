import 'package:google_fonts/google_fonts.dart';
import 'package:gopay/responsive.dart';
import 'package:gopay/widgets/header.dart';
import 'package:flutter/material.dart';

import '../../constants.dart';
import 'components/all_transferts.dart';
import 'components/nouveau_transfert.dart';

class DemandeTransfertScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: SingleChildScrollView(
        padding: EdgeInsets.all(defaultPadding),
        child: Column(
          children: [
            Header(),
            const SizedBox(height: defaultPadding),
            Divider(
              thickness: 1,
              color: primaryColor.withOpacity(0.6),
            ),
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  flex: 5,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 32),
                      const NouveauTransfert(),
                      const SizedBox(height: 48),
                      Text(
                        "Mes demandes de transferts",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.black38,
                              fontWeight: FontWeight.w500,
                              fontSize: 20),
                        ),
                      ),
                      Divider(
                        thickness: 1,
                        color: primaryColor.withOpacity(0.6),
                      ),
                      const SizedBox(height: defaultPadding),
                      SizedBox(
                          height: MediaQuery.of(context).size.height * 0.6,
                          child: const AllTransferts()),
                      // TempsReel(),
                      // if (Responsive.isMobile(context))
                      //   SizedBox(height: defaultPadding),
                      //if (Responsive.isMobile(context)) const StarageDetails(),
                    ],
                  ),
                ),
                if (!Responsive.isMobile(context))
                  SizedBox(width: defaultPadding),
                // On Mobile means if the screen is less than 850 we dont want to show it
                // if (!Responsive.isMobile(context))
                //   Expanded(
                //     flex: 2,
                //     // child: StarageDetails(),
                //     child: Container(
                //       decoration: BoxDecoration(
                //         color: secondaryColor,
                //         borderRadius:
                //             const BorderRadius.all(Radius.circular(10)),
                //       ),
                //     ),
                //   ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
