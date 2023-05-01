import 'package:data_table_2/data_table_2.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_fonts/google_fonts.dart';

import '../../../constants.dart';
import '../../../controllers/demande_tranfert_controller.dart';
import '../../../models/DemandeTransfert.dart';

class AllTransferts extends StatefulWidget {
  const AllTransferts({Key? key}) : super(key: key);

  @override
  State<AllTransferts> createState() => _AllTransfertsState();
}

class _AllTransfertsState extends State<AllTransferts> {
  DemandeTransfertController demandeTransfertController =
      Get.put(DemandeTransfertController());

  String? value;

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(defaultPadding),
      decoration: BoxDecoration(
        color: primaryColor,
        borderRadius: const BorderRadius.all(Radius.circular(10)),
        border: Border.all(color: primaryColor, width: 1),
      ),
      child: Obx(
        () => (!demandeTransfertController.isDTLoaded)
            ? (demandeTransfertController.demandeTransferts.isNotEmpty)
                ? DataTable2(
                    // columnSpacing: defaultPadding,
                    minWidth: 600,
                    smRatio: 0.5,
                    lmRatio: 2,
                    columnSpacing: 0,
                    horizontalMargin: 0,
                    dividerThickness: 2,

                    dataTextStyle: GoogleFonts.poppins(
                      textStyle: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w400,
                          fontSize: 16),
                    ),
                    headingTextStyle: GoogleFonts.poppins(
                      textStyle: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w500,
                          fontSize: 16),
                    ),
                    columns: const [
                      DataColumn2(
                        label: Text("No"),
                        size: ColumnSize.S,
                      ),
                      DataColumn2(
                        label: Text("Montant"),
                      ),
                      DataColumn2(
                        label: Text("Destination"),
                      ),
                      DataColumn2(
                        label: Text("Status"),
                      ),
                      DataColumn2(
                        label: Text("Note"),
                      ),
                      DataColumn2(
                        label: Text("Date"),
                      ),
                    ],
                    rows: List.generate(
                      demandeTransfertController.demandeTransferts.length,
                      (index) => demandeTransfertsDataRow(
                          demandeTransfertController.demandeTransferts[index],
                          index),
                    ),
                  )
                : Center(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Text(
                        "Aucune transaction n'a été effectuée",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.w500,
                              fontSize: 20),
                        ),
                      ),
                    ),
                  )
            : const Center(
                child: SizedBox(
                height: 60,
                width: 60,
                child: CircularProgressIndicator(
                  color: Colors.white,
                ),
              )),
      ),
    );
  }

  DataRow demandeTransfertsDataRow(
      DemandeTransfert demandeTransfert, int index) {
    return DataRow(
      cells: [
        DataCell(Text((index + 1).toString())),
        DataCell(Text(demandeTransfert.montant)),
        DataCell(Text(demandeTransfert.destination)),
        DataCell(Text(demandeTransfert.destination)),
        DataCell(Text(demandeTransfert.note)),
        DataCell(Text(demandeTransfert.date)),
      ],
    );
  }
}
