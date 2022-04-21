import React from 'react'
import { View, Text, StyleSheet } from 'react-native';
import { GestureHandlerRootView, PanGestureHandler, TapGestureHandler } from 'react-native-gesture-handler';
import LinearGradient from 'react-native-linear-gradient'
import Animated, { event, interpolateColor, useAnimatedGestureHandler, useAnimatedStyle, useDerivedValue, useSharedValue, withSpring, withTiming } from 'react-native-reanimated';


export default function ColorPicker(props) {

    //console.log(props.colors);

    const translateX = useSharedValue(0);

    const translateY = useSharedValue(0);

    const scale  = useSharedValue(1);

    const adjustedTranslateX = useDerivedValue(() => {
        return Math.min(
            Math.max(translateX.value, 0), 
            props.maxWidth - CIRCLE_PICKER_SIZE
        );
    });

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                {translateX : adjustedTranslateX.value},
                {scale : scale.value},
                {translateY : translateY.value}
            ]
        }
    });

    const inputRange = props.colors.map((_,index) => (index / props.colors.length) * props.maxWidth );

    const animatedInteralPickerStyle = useAnimatedStyle(() => {

        const backgroundColor = interpolateColor(
            translateX.value,
            inputRange,
            props.colors
        );

        props.onColorChanged(backgroundColor);

        return {
            backgroundColor
        }

    });

    const panGestureEvent = useAnimatedGestureHandler({
        onStart : (_, context) => {
            context.x = adjustedTranslateX.value; 
            //translateY.value = withSpring(- CIRCLE_PICKER_SIZE);
            //scale.value = withSpring(1.2);
        },
        onActive : (event, context) => {
            //console.log(event);
            translateX.value = event.translationX + context.x;
        },
        onEnd : () => {
            translateY.value = withSpring(0);
            scale.value = withSpring(1);
        }
    });

    const tapGestureEvent = useAnimatedGestureHandler({
        onStart : (event) => {
            translateY.value = withSpring(- CIRCLE_PICKER_SIZE);
            scale.value = withSpring(1.2);
            translateX.value = withTiming(event.absoluteX - CIRCLE_PICKER_SIZE);
        },
        onEnd : () => {
            translateY.value = withSpring(0);
            scale.value = withSpring(1);
        }
    })

    return (
        <GestureHandlerRootView>
            <TapGestureHandler onGestureEvent={tapGestureEvent}>
                <Animated.View>
                    <PanGestureHandler onGestureEvent={panGestureEvent}>
                        <Animated.View style={{justifyContent : 'center'}}>
                            <LinearGradient 
                                colors={props.colors} 
                                style={props.style}
                                start={props.start}
                                end={props.end}
                            />
                            <Animated.View style={[styles.picker, animatedStyle]} >
                                <Animated.View style={[styles.internalPicker, animatedInteralPickerStyle]} />
                            </Animated.View>
                        </Animated.View>
                    </PanGestureHandler>
                </Animated.View>
            </TapGestureHandler>
        </GestureHandlerRootView>
    )
}

const CIRCLE_PICKER_SIZE = 35;

const INTERNAL_PICKER_SIZE = CIRCLE_PICKER_SIZE / 2;

const styles = StyleSheet.create({
    picker : {
        position : 'absolute',
        backgroundColor : '#fff',
        width : CIRCLE_PICKER_SIZE,
        height : CIRCLE_PICKER_SIZE,
        borderRadius : CIRCLE_PICKER_SIZE / 2,
        alignItems : 'center',
        justifyContent : 'center'
    },
    internalPicker : {
        width : INTERNAL_PICKER_SIZE,
        height : INTERNAL_PICKER_SIZE,
        borderRadius : INTERNAL_PICKER_SIZE / 2,
        borderWidth : 1.0,
        borderColor : 'rgba(0,0,0,0.1)'
    }
});