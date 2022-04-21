import React from 'react'
import { View, StyleSheet, Dimensions, Text } from 'react-native'
import Animated, { Extrapolate, interpolate, useAnimatedStyle } from 'react-native-reanimated';

const {height, width} = Dimensions.get('window');

const SIZE = width * 0.7

export default function Page(props) {

    const inputRange = [(props.index - 1) * width, props.index * width, (props.index + 1) * width ];

    const animatedStyle = useAnimatedStyle(() => {

        const scale = interpolate(
            props.translateX.value, 
            inputRange, 
            [0, 1, 0],
            Extrapolate.CLAMP
        );

        const borderRadius = interpolate(
            props.translateX.value,
            inputRange,
            [0, SIZE / 2, 0],
            Extrapolate.CLAMP
        )

        return {
            borderRadius,
            transform : [
                {scale},
            ],
        };
    });

    const animatedTextStyle = useAnimatedStyle(() => {

        const translateY = interpolate(
            props.translateX.value,
            inputRange,
            [height / 2, 0, -height / 2],
            Extrapolate.CLAMP
        );

        const opacity = interpolate(
            props.translateX.value,
            inputRange,
            [-2, 1, -2],
            Extrapolate.CLAMP
        );

        return {
            opacity,
            transform : [
                {translateY}
            ]
        }
    })

    return (
        <View 
            style={[
                styles.pageContainer,
                {backgroundColor : `rgba(0, 0, 256, 0.${props.index})`}
            ]}
        >
            <Animated.View style={[styles.square, animatedStyle]}>
                <Animated.View style={[{flex:1, justifyContent:'center',alignItems:'center'},animatedTextStyle]}>
                    <Text style={{fontSize:65,color:'white', textTransform:'uppercase', fontWeight:'700'}}>{props.title}</Text>
                </Animated.View>
                
            </Animated.View>

        </View>
    )
}

const styles = StyleSheet.create({
    pageContainer : {
        height,
        width,
        alignItems: 'center',
        justifyContent: 'center'
    },
    square : {
        height: SIZE,
        width: SIZE,
        backgroundColor: 'rgba(0, 0, 256, 0.4)'
    }
})