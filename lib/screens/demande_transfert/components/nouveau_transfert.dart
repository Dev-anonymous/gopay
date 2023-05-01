import 'package:google_fonts/google_fonts.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../constants.dart';
import '../../../controllers/demande_tranfert_controller.dart';
import '../../../responsive.dart';

class NouveauTransfert extends StatefulWidget {
  const NouveauTransfert({Key? key}) : super(key: key);

  @override
  State<NouveauTransfert> createState() => _NouveauTransfertState();
}

class _NouveauTransfertState extends State<NouveauTransfert> {
  final DemandeTransfertController _demandeTransfertController =
      Get.put(DemandeTransfertController());

  final _formKey = GlobalKey<FormState>();

  final TextEditingController _numeroController = TextEditingController();
  final TextEditingController _montantController = TextEditingController();

  String? devise;

  List<String> devises = ['CDF', 'USD'];

  @override
  void dispose() {
    _numeroController.dispose();
    _montantController.dispose();

    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Obx(() => Center(
          child: Container(
              width: Responsive.isMobile(context) ? double.infinity : 500,
              decoration: const BoxDecoration(
                borderRadius: BorderRadius.all(Radius.circular(18)),
                color: primaryColor,
              ),
              child: Form(
                key: _formKey,
                child: Padding(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 32.0, vertical: 32),
                    child: (!_demandeTransfertController.isDTLoaded)
                        ? Column(children: [
                            Text(
                              "Demande de tranfert",
                              style: GoogleFonts.poppins(
                                textStyle: const TextStyle(
                                    color: Colors.white,
                                    fontWeight: FontWeight.w500,
                                    fontSize: 20),
                              ),
                            ),
                            const SizedBox(height: 16),
                            Text(
                              "Faites un tranferer d'argent vers un numero",
                              textAlign: TextAlign.center,
                              style: GoogleFonts.poppins(
                                textStyle: const TextStyle(
                                    color: Colors.white,
                                    fontWeight: FontWeight.w400,
                                    fontSize: 13),
                              ),
                            ),
                            const SizedBox(height: 32),
                            TextFormField(
                              controller: _numeroController,
                              keyboardType: TextInputType.phone,
                              validator: ((value) {
                                if (value == null || value.isEmpty) {
                                  return 'Veuillez saisir le numero';
                                } else if (!value.isPhoneNumber) {
                                  return 'Veuillez saisir un numero valide (ex: 97 123 12 21)';
                                }

                                return null;
                              }),
                              style: GoogleFonts.poppins(
                                textStyle: const TextStyle(
                                    color: Colors.black,
                                    fontWeight: FontWeight.w500,
                                    fontSize: 14),
                              ),
                              decoration: InputDecoration(
                                hintText: " Numero de telephone",
                                hintStyle: GoogleFonts.poppins(
                                  textStyle: const TextStyle(
                                      color: Colors.black,
                                      fontWeight: FontWeight.w400,
                                      fontSize: 14),
                                ),
                                errorStyle: GoogleFonts.poppins(
                                  textStyle: const TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.w400,
                                      fontSize: 14),
                                ),
                                fillColor: secondaryColor,
                                filled: true,
                                border: const OutlineInputBorder(
                                  borderSide: BorderSide.none,
                                  borderRadius:
                                      BorderRadius.all(Radius.circular(10)),
                                ),
                              ),
                            ),
                            const SizedBox(height: defaultPadding),
                            TextFormField(
                              controller: _montantController,
                              keyboardType: TextInputType.number,
                              validator: ((value) {
                                if (value == null || value.isEmpty) {
                                  return 'Veuillez saisir une somme d\'argent';
                                } else if (int.parse(value) <= 0) {
                                  return 'La somme saisie n\'est pas correcte';
                                }

                                return null;
                              }),
                              style: GoogleFonts.poppins(
                                textStyle: const TextStyle(
                                    color: Colors.black,
                                    fontWeight: FontWeight.w500,
                                    fontSize: 14),
                              ),
                              decoration: InputDecoration(
                                hintText: "Montant à transférer",
                                hintStyle: GoogleFonts.poppins(
                                  textStyle: const TextStyle(
                                      color: Colors.black,
                                      fontWeight: FontWeight.w400,
                                      fontSize: 14),
                                ),
                                errorStyle: GoogleFonts.poppins(
                                  textStyle: const TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.w400,
                                      fontSize: 14),
                                ),
                                fillColor: secondaryColor,
                                filled: true,
                                border: const OutlineInputBorder(
                                  borderSide: BorderSide.none,
                                  borderRadius:
                                      BorderRadius.all(Radius.circular(10)),
                                ),
                              ),
                            ),
                            const SizedBox(height: defaultPadding),
                            Container(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 16),
                              decoration: BoxDecoration(
                                  color: secondaryColor,
                                  borderRadius: BorderRadius.circular(10)),
                              child: DropdownButtonHideUnderline(
                                child: DropdownButton(
                                    value: devise,
                                    isExpanded: true,
                                    iconSize: 36,
                                    icon: const Icon(
                                      Icons.arrow_drop_down,
                                      color: Colors.black,
                                    ),
                                    hint: const Text('Devise',
                                        style: TextStyle(
                                            color: Colors.black,
                                            fontSize: 14,
                                            fontWeight: FontWeight.w500)),
                                    items: devises.map(_buildMenuItem).toList(),
                                    onChanged: (value) => setState(() {
                                          devise = value;
                                        })),
                              ),
                            ),
                            const SizedBox(height: 32),
                            ElevatedButton(
                              style: TextButton.styleFrom(
                                disabledBackgroundColor: Colors.grey,
                                foregroundColor: Colors.white,
                                backgroundColor: bgColor,
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 32,
                                  vertical: 16,
                                ),
                              ),
                              onPressed: () async {
                                print(devise);
                                if (_formKey.currentState!.validate() &&
                                    devise != null &&
                                    devise!.isNotEmpty) {
                                  _formKey.currentState!.reset();
                                  await _demandeTransfertController
                                      .nouvelleDemandeTransfert(
                                          numero: _numeroController.text,
                                          montant: _montantController.text,
                                          devise: devise!);
                                }
                              },
                              child: Text(
                                "Valider",
                                style: GoogleFonts.poppins(
                                  textStyle: const TextStyle(
                                      color: Colors.black,
                                      fontWeight: FontWeight.w500,
                                      fontSize: 14),
                                ),
                              ),
                            ),
                          ])
                        : const Center(
                            child: SizedBox(
                                height: 60,
                                width: 60,
                                child: CircularProgressIndicator(
                                  color: Colors.white,
                                )),
                          )),
              )),
        ));
  }

  DropdownMenuItem<String> _buildMenuItem(String devise) {
    return DropdownMenuItem<String>(
        value: devise,
        child: Text(
          devise,
          style: GoogleFonts.poppins(
            textStyle: const TextStyle(
                color: Colors.black, fontWeight: FontWeight.w500, fontSize: 16),
          ),
        ));
  }
}
