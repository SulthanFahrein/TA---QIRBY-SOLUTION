import 'package:flutter/material.dart';

class IconButtonCustom extends StatelessWidget {
  final Color color;
  final IconData icon;
  final VoidCallback? onTap;

  IconButtonCustom({
    required this.color,
    required this.icon,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Material(
      color: color,
      borderRadius: BorderRadius.circular(45),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(45),
        child: SizedBox(
          height: 45,
          width: 45,
          child: Center(
            child: Icon(
              icon,
              color: Colors.white,
            ),
          ),
        ),
      ),
    );
  }
}
