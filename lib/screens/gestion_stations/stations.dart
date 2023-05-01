import 'package:flutter/material.dart';

import '../../constants.dart';
import '../../responsive.dart';
import '../../widgets/header.dart';

class GestionStationScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(defaultPadding),
        child: Column(
          children: [
            Header(),
            const SizedBox(height: defaultPadding),
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  flex: 5,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      //AddStation(),
                    ],
                  ),
                ),
                if (!Responsive.isMobile(context))
                  const SizedBox(width: defaultPadding),
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
